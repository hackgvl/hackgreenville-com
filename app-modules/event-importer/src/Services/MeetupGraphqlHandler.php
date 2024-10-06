<?php

namespace HackGreenville\EventImporter\Services;

use App\Enums\EventServices;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MeetupGraphqlHandler extends AbstractEventHandler
{
    protected ?string $next_page_url = null;
    protected int $per_page_limit = 100;
    protected int $cache_ttl_minutes = 10;
    protected string $cache_key = 'meetup_graphql_events';

    protected function mapIntoEventData(array $data): EventData
    {
        $event = $data['node'];
        $event_time = Carbon::parse($event['dateTime']);

        return EventData::from([
            'id' => $event['id'],
            'name' => $event['title'],
            'description' => $event['description'],
            'url' => $event['eventUrl'],
            'starts_at' => $event_time,
            // Meetup (graphql) does not provide an event end time
            'ends_at' => $event_time->copy()->addHours(2),
            'timezone' => $event_time->timezoneName,
            'cancelled_at' => match ($event['status']) {
                'CANCELLED' => now(),
                'cancelled' => now(),
                default => null
            },
            'rsvp' => $event['going'],
            'service' => EventServices::MeetupGraphql,
            'service_id' => $event['id'],
            'venue' => $this->mapIntoVenueData($data),
        ]);
    }

    protected function mapIntoVenueData(array $data): ?VenueData
    {
        $event = $data['node'];

        if ( ! isset($event['venue'])) {
            return null;
        }

        if ($event['eventType'] === 'ONLINE') {
            return null;
        }

        $venue = $event['venue'];

        try {
            return VenueData::from([
                'id' => $venue['id'],
                'name' => $venue['name'],
                'address' => $venue['address'] ?? '',
                'city' => $venue['city'],
                'state' => $venue['state'],
                'zip' => $venue['postalCode'],
                'country' => $venue['country'],
                'lat' => $venue['lat'],
                'lon' => $venue['lng'],
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }
    }

    protected function eventResults(int $page): Collection
    {
        $response = $this->getMeetupEvents();

        $this->determineNextPage($response);

        $data = $response->collect();
        $groupData = $data['data']['groupByUrlname'];

        $pastEvents = $groupData['pastEvents']['edges'];
        $upcomingEvents = $groupData['upcomingEvents']['edges'];

        $events = array_merge($pastEvents, $upcomingEvents);
        $events = $this->filterEvents($events);

        return collect($events);
    }

    protected function determineNextPage(Response $response): void
    {
        $data = $response->collect();
        $upcomingEvents = $data['data']['groupByUrlname']['upcomingEvents'];
        $pageInfo = $upcomingEvents['pageInfo'];

        if (false === $pageInfo['hasNextPage']) {
            $this->next_page_url = null;
            return;
        }

        $this->next_page_url = $upcomingEvents['pageInfo']['endCursor'];
    }

    private function getMeetupEvents(): Response
    {
        $bearer_token = $this->getBearerToken();

        $urlname = $this->org->service_api_key;
        $skip = $this->next_page_url !== null ? ', after: "' . $this->next_page_url . '"' : '';

        $query = <<<GQL
        query {
          groupByUrlname(urlname: "{$urlname}" ) {
            id
            name
            pastEvents(input: { first: {$this->per_page_limit} }, sortOrder: DESC) {
              edges {
                cursor
                node {
                  id
                  title
                  eventUrl
                  description
                  dateTime
                  eventType
                  status
                  going
                  createdAt
                  venue {
                    id
                    name
                    address
                    city
                    state
                    postalCode
                    country
                    lat
                    lng
                  }
                }
              }
              pageInfo {
                startCursor
                endCursor
                hasNextPage
              }
              count
            }
            upcomingEvents(input: { first: {$this->per_page_limit} {$skip} }, filter: { includeCancelled: true }, sortOrder: ASC) {
              edges {
                cursor
                node {
                  id
                  title
                  eventUrl
                  description
                  dateTime
                  eventType
                  status
                  going
                  createdAt
                  venue {
                    id
                    name
                    address
                    city
                    state
                    postalCode
                    country
                    lat
                    lng
                  }
                }
              }
              pageInfo {
                startCursor
                endCursor
                hasNextPage
              }
              count
            }
          }
        }
        GQL;

        // We may get rate limited on the number of calls we make here
        // Let's retry a few times with a delay in between
        $response = Http::retry(3, 1000)->baseUrl('https://api.meetup.com/')
            ->withToken($bearer_token['access_token'])
            ->throw()
            ->post("/gql", [
                'query' => $query
            ]);

        return $response;
    }

    // Meetup's GraphQL API does not support date filtering on its API
    // We need to filter the events ourselves
    private function filterEvents(array $events): array
    {
        $start_date = now()->subDays($this->max_days_in_past)->startOfDay();
        $end_date = now()->addDays($this->max_days_in_future)->endOfDay();

        $filtered_events = [];
        $filtered_events_hash = [];

        foreach($events as $eventData) {
            $event = $eventData['node'];
            $event_hash = md5($event['dateTime'] . $event['title']);

            $eventDate = Carbon::parse($event['dateTime']);
            if ($eventDate < $start_date || $eventDate > $end_date) {
                continue;
            }

            // Sometimes Meetup will return multiple events with the same title and date
            // We need to filter out duplicates
            if (in_array($event_hash, $filtered_events_hash)) {
                continue;
            }

            $filtered_events[] = $eventData;
            $filtered_events_hash[] = $event_hash;
        }

        return $filtered_events;
    }

    private function getBearerToken()
    {
        if (Cache::has($this->cache_key)) {
            return Cache::get($this->cache_key);
        }

        $this->validateConfigValues();

        $jwt_key = $this->getJwtKey();

        $response = Http::asForm()->throw()->post('https://secure.meetup.com/oauth2/access', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt_key,
        ]);

        $key_data = $response->json();

        // Save JWT key to cache for next event import
        Cache::put($this->cache_key, $key_data, now()->addMinutes($this->cache_ttl_minutes - 1));

        return $key_data;
    }

    private function getJwtKey()
    {
        $file_path = config('event-import-handlers.meetup_graphql_private_key_path');

        if ( ! file_exists($file_path)) {
            throw new RuntimeException('File path ' . $file_path . ' does not exist.');
        }

        $privateKey = file_get_contents($file_path);
        $headers = [
            'kid' => config('event-import-handlers.meetup_graphql_private_key_id'),
        ];
        $payload = [
            'iss' => config('event-import-handlers.meetup_graphql_client_id'),
            'sub' => config('event-import-handlers.meetup_graphql_member_id'),
            'aud' => 'api.meetup.com',
            'iat' => time(),
            'exp' => time() + (60 * $this->cache_ttl_minutes),
        ];
        return JWT::encode($payload, $privateKey, 'RS256', null, $headers);
    }

    private function validateConfigValues(): void
    {
        if (config('event-import-handlers.meetup_graphql_client_id') === null) {
            throw new RuntimeException('meetup_graphql_client_id config value must be set.');
        }

        if (config('event-import-handlers.meetup_graphql_member_id') === null) {
            throw new RuntimeException('meetup_graphql_member_id config value must be set.');
        }

        if (config('event-import-handlers.meetup_graphql_private_key_id') === null) {
            throw new RuntimeException('meetup_graphql_private_key_id config value must be set.');
        }

        if (config('event-import-handlers.meetup_graphql_private_key_path') === null) {
            throw new RuntimeException('meetup_graphql_private_key_path config value must be set.');
        }
    }
}
