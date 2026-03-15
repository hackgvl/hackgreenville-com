<?php

namespace HackGreenville\EventImporter\Services;

use App\Enums\EventServices;
use Carbon\Carbon;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use HackGreenville\EventImporter\Services\Concerns\MeetupGraphqlAuthentication;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/*
NOTE: This importer has been deprecated in favor of the MeetupGraphqlExtHandler which uses the newer
      /gql-ext endpoint. This handler remains in the codebase for reference and historical purposes.
*/
class MeetupGraphqlHandler extends AbstractEventHandler
{
    use MeetupGraphqlAuthentication;
    protected ?string $next_page_url = null;
    protected int $per_page_limit = 100;

    protected function mapIntoEventData(array $data): EventData
    {
        $event = $data['node'];

        $startsAt = Carbon::parse($event['dateTime']);

        $map = EventData::from([
            'id' => $event['id'],
            'name' => $event['title'],
            'description' => $event['description'],
            'url' => $event['eventUrl'],
            'starts_at' => $startsAt,
            // Meetup (graphql) does not provide an event end time
            'ends_at' => $startsAt->copy()->addHours(2),
            'timezone' => $startsAt->timezoneName,
            'cancelled_at' => match ($event['status']) {
                'CANCELLED' => now(),
                'cancelled' => now(),
                default => null
            },
            'rsvp' => $event['going'],
            'service' => EventServices::MeetupGraphql,
            'service_id' => $event['token'] ?? $event['id'],
            'venue' => $this->mapIntoVenueData($data)
        ]);

        $this->meetupDebugLog('MeetupGraphql', [
            'org_service_key' => $this->org->service_api_key,
            'service_id' => $event['id'],
            'token' => $event['token'] ?? null,
            'starts_at' => $map->starts_at->toISOString(),
            'name' => $map->name,
        ]);

        return $map;
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
        Log::info('Fetching events from Meetup GraphQL API', ['service_key' => $this->org->service_api_key]);

        $response = $this->getMeetupEvents();

        $this->determineNextPage($response);

        $data = $response->collect();
        $groupData = $data['data']['groupByUrlname'];

        $pastEvents = $groupData['pastEvents']['edges'];
        $upcomingEvents = $groupData['upcomingEvents']['edges'];

        $startDate = now()->subDays($this->max_days_in_past)->startOfDay();
        $endDate = now()->addDays($this->max_days_in_future)->endOfDay();

        return collect(array_merge($pastEvents, $upcomingEvents))
            ->filter(fn (array $event) => Carbon::parse($event['node']['dateTime'])->between($startDate, $endDate))
            ->values();
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
                  token
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
                  token
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

        $response = Http::baseUrl('https://api.meetup.com/')
            ->withToken($bearer_token['access_token'])
            ->throw()
            ->post("/gql", [
                'query' => $query
            ]);

        return $response;
    }

}
