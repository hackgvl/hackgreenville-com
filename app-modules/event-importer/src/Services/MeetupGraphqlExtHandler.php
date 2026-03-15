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

class MeetupGraphqlExtHandler extends AbstractEventHandler
{
    use MeetupGraphqlAuthentication;
    protected ?string $next_page_url = null;
    protected int $per_page_limit = 100;

    protected function mapIntoEventData(array $data): EventData
    {
        $event = $data['node'];

        $going = $event['rspvs']['yesCount'] ?? 0;

        $startsAt = Carbon::parse($event['dateTime']);

        $map = EventData::from([
            'id' => $event['id'],
            'name' => $event['title'],
            'description' => $event['description'],
            'url' => $event['eventUrl'],
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHours(2),
            'timezone' => $startsAt->timezoneName,
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

        $this->meetupDebugLog('Query', [
            'org_service_key' => $this->org->service_api_key,
            'query' => $query,
        ]);

        $response = Http::baseUrl('https://api.meetup.com/')
            ->withToken($bearer_token['access_token'])
            ->throw()
            ->post("/gql-ext", [
                'query' => $query
            ]);

        return $response;
    }

}
