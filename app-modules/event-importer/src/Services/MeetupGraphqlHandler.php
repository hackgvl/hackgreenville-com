<?php

namespace HackGreenville\EventImporter\Services;

use App\Enums\EventServices;
use App\Enums\EventType;
use Carbon\Carbon;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class MeetupGraphqlHandler extends AbstractEventHandler
{
    protected ?string $next_page_url = null;
    protected int $per_page_limit = 100;

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
        if (null === $this->next_page_url) {
            $response = $this->initialApiCall();
        }

        $data = $response->collect();
        $groupData = $data['data']['groupByUrlname'];
        $pastEvents = $groupData['pastEvents']['edges'];
        $upcomingEvents = $groupData['upcomingEvents']['edges'];

        $pastEvents = $this->filterEvents($pastEvents, false);
        $upcomingEvents = $this->filterEvents($upcomingEvents, true);

        $this->determineNextPage($response);

        return collect(array_merge($pastEvents, $upcomingEvents));
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

    private function initialApiCall(): Response
    {
        $response = Http::baseUrl('https://api.meetup.com/')
            ->throw()
            ->post("/gql");

        if ($total_results = $response->header('X-Total-Count')) {
            $this->page_count = ceil($total_results / $this->per_page_limit);
        }

        return $response;
    }

    private function filterEvents(array $events, bool $isUpcoming): array
    {
      $filteredEvents = [];
      foreach($events as $event) {
        $eventDate = Carbon::parse($event['node']['dateTime']);
        if (!$isUpcoming) {
          if ($eventDate < now()->subDays($this->max_days_in_future)->startOfDay()) {
            continue;
          }
          $filteredEvents[] = $event;
          continue;
        }
        if ($eventDate > now()->addDays($this->max_days_in_past)->startOfDay()) {
          continue;
        }
        $filteredEvents[] = $event;
      }

      return $filteredEvents;
    }
}
