<?php

namespace HackGreenville\EventImporter\Services;

use App\Enums\EventServices;
use App\Enums\EventType;
use App\Models\Org;
use Carbon\Carbon;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use HackGreenville\EventImporter\Providers\MeetupGraphqlTokenProvider;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class MeetupGraphqlHandler extends AbstractEventHandler
{
    protected ?string $next_page_url = null;
    protected int $per_page_limit = 100;
    protected MeetupGraphqlTokenProvider $tokenProvider;

    public function __construct(
        public Org $org
    ) {
        $this->tokenProvider = new MeetupGraphqlTokenProvider;
        parent::__construct($org);
    }

    protected function mapIntoEventData(array $data): EventData
    {
        $event = $data['node'];
        return EventData::from([
            'id' => $event['id'],
            'name' => $event['title'],
            'description' => $event['description'],
            'url' => $event['eventUrl'],
            'starts_at' => Carbon::parse($event['dateTime']),
            'event_type' => match ($event['eventType']) {
                'ONLINE' => EventType::Online,
                'PHYSICAL' => EventType::Live,
                default => throw new RuntimeException("Unable to determine event type {$event['eventType']}"),
            },
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
        }

        $this->next_page_url = $upcomingEvents['pageInfo']['endCursor'];
    }

    private function getMeetupEvents(): Response
    {
        $bearer_token = $this->tokenProvider->getBearerToken();

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

        $response = Http::baseUrl('https://api.meetup.com/')
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
        $end_date = now()->addDays($this->max_days_in_future)->startOfDay();

        $filtered_events = [];

        foreach($events as $event) {
            $eventDate = Carbon::parse($event['node']['dateTime']);
            if ($eventDate < $start_date || $eventDate > $end_date) {
                continue;
            }

            $filtered_events[] = $event;
        }

        return $filtered_events;
    }
}
