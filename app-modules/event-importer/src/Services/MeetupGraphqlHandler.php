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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MeetupGraphqlHandler extends AbstractEventHandler
{
    protected ?string $next_page_url = null;
    protected int $per_page_limit = 100;

    protected function mapIntoEventData(array $data): EventData
    {
        $event = $data['node'];

        $going = $event['rspvs']['yesCount'] ?? 0;

        $map = EventData::from([
            'id' => $event['id'],
            'name' => $event['title'],
            'description' => $event['description'],
            'url' => $event['eventUrl'],
            'starts_at' => Carbon::parse($event['dateTime']),
            'ends_at' => Carbon::parse($event['dateTime'])->addHours(2),
            'timezone' => Carbon::parse($event['dateTime'])->timezoneName,
            'cancelled_at' => match ($event['status']) {
                'CANCELLED' => now(),
                'cancelled' => now(),
                default => null
            },
            'rsvp' => $going,
            'service' => EventServices::MeetupGraphql,
            'service_id' => $event['token'] ?? $event['id'],
            'venue' => $this->mapIntoVenueData($data)
        ]);

        if ($this->debug_log_enabled) {
            Log::info('Mapped MeetupGraphql event data', [
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/meetup-graphql.log'),
                ])->info('MeetupGraphql', [
                    'org_service_key' => $this->org->service_api_key,
                    'service_id' => $event['id'],
                    'token' => $event['token'] ?? null,
                    'starts_at' => $map->starts_at->toISOString(),
                    'name' => $map->name,
                ])
            ]);
        }

        return $map;
    }

    protected function mapIntoVenueData(array $data): ?VenueData
    {
        $event = $data['node'];
        $venues = $event['venues'];

        if ( ! isset($venues) || count($venues) === 0) {
            return null;
        }

        if ($event['eventType'] === 'ONLINE') {
            return null;
        }

        $venue = $venues[0];

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
                'lon' => $venue['lon'],
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }
    }

    protected function eventResults(int $page): Collection
    {
        Log::info('Fetching events from Meetup GraphQL API', ['service_key' => $this->org->service_api_key]);

        $response = $this->getMeetupEvents();

        $this->determineNextPage($response);

        $data = $response->collect();
        $groupData = $data['data']['groupByUrlname'];

        if ($groupData === null) {
            Log::warning('No group data found for Meetup GraphQL organization', ['service_key' => $this->org->service_api_key]);
            return collect();
        }

        // The /gql-ext API endpoint returns only upcoming events.
        $events = $groupData['events']['edges'];

        return collect($events);
    }

    protected function determineNextPage(Response $response): void
    {
        $data = $response->collect();
        $groupData = $data['data']['groupByUrlname'];

        // With the v2 API, if no upcoming events are found the group data is null
        if ($groupData === null) {
            $this->next_page_url = null;
            return;
        }

        $events = $groupData['events'];
        $pageInfo = $events['pageInfo'];

        if (false === $pageInfo['hasNextPage']) {
            $this->next_page_url = null;
            return;
        }

        $this->next_page_url = $events['pageInfo']['endCursor'];
    }

    private function getMeetupEvents(): Response
    {
        $bearer_token = $this->getBearerToken();

        $urlname = $this->org->service_api_key;
        $skip = $this->next_page_url !== null ? ', after: "' . $this->next_page_url . '"' : '';
        $beforeDateTime = "\"" . now()->addDays($this->max_days_in_future)->startOfDay()->toIso8601String() . "\"";
        $afterDateTime = "\"" . now()->subDays($this->max_days_in_past)->startOfDay()->toIso8601String() . "\"";

        $query = <<<GQL
        query {
          groupByUrlname(urlname: "{$urlname}" ) {
            id
            name
            events(first: {$this->per_page_limit}, sort: DESC, filter: { afterDateTime: {$afterDateTime}, beforeDateTime: {$beforeDateTime} } {$skip}) {
              edges {
                cursor
                node {
                  id # a unique but unstable identifier for the event. recurring events may change the value of the identifier
                  token # a unique and stable identifier for the event.
                  title
                  eventUrl
                  description
                  createdTime
                  dateTime # scheduled time of event
                  endTime # end time of event
                  eventType # HYBRID/ ONLINE / PHYSICAL
                  status # ACTIVE / AUTOSCHED / AUTOSCHED_CANCELLED / AUTOSCHED_DRAFT / AUTOSCHED_FINISHED / BLOCKED / CANCELLED / CANCELLED_PERM / DRAFT / PAST / PENDING / PROPOSED / TEMPLATE          
                  rsvps {
                    yesCount # how many people are going
                  }
                  venues {
                    id
                    name
                    address
                    city
                    state
                    postalCode
                    country
                    lat
                    lon
                    venueType # 'online' for virtual events. empty string for in-person events.
                  }
                }
              }
              pageInfo {
                startCursor
                endCursor
                hasNextPage
              }
              totalCount
            }
          }
        }
        GQL;

        if ($this->debug_log_enabled) {
            Log::info('MeetupGraphql Query', [
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/meetup-graphql.log'),
                ])->info('Query', [
                    'org_service_key' => $this->org->service_api_key,
                    'query' => $query,
                ])
            ]);
        }

        $response = Http::baseUrl('https://api.meetup.com/')
            ->withToken($bearer_token['access_token'])
            ->throw()
            ->post("/gql-ext", [
                'query' => $query
            ]);

        return $response;
    }

    private function getBearerToken()
    {
        $this->validateConfigValues();

        $jwt_key = $this->getJwtKey();

        $response = Http::asForm()->throw()->post('https://secure.meetup.com/oauth2/access', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt_key,
        ]);

        return $response->json();
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
            'exp' => time() + 240,
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
