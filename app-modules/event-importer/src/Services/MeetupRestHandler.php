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
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class MeetupRestHandler extends AbstractEventHandler
{
    protected ?string $next_page_url = null;

    protected int $per_page_limit = 100;

    public function initialApiCall(): Response
    {
        $response = Http::baseUrl('https://api.meetup.com/')
            ->withQueryParameters([
                'sign' => true,
                'photo-host' => 'public',
                'status' => 'upcoming,cancelled,past',
                'page' => $this->per_page_limit,
                'no_earlier_than' => now()->subDays($this->max_days_in_past)->startOfDay()->format('Y-m-d\TH:i:s'),
                'no_later_than' => now()->addDays($this->max_days_in_future)->endOfDay()->format('Y-m-d\TH:i:s'),
            ])
            ->throw()
            ->get("{$this->org->service_api_key}/events");

        if ($total_results = $response->header('X-Total-Count')) {
            $this->page_count = ceil($total_results / $this->per_page_limit);
        }

        return $response;
    }

    protected function mapIntoEventData(array $data): EventData
    {
        return EventData::from([
            'id' => $data['id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'url' => $data['link'],
            'starts_at' => Carbon::createFromTimestampMs($data['time']),
            // Meetup (rest) does not provide an event end time
            'ends_at' => Carbon::createFromTimestampMs($data['time'])->addHours(2),
            'timezone' => Carbon::createFromTimestampMs($data['time'])->utcOffset($data['utc_offset'] / 1000)->timezoneName,
            'event_type' => match ($data['eventType']) {
                'ONLINE' => EventType::Online,
                'PHYSICAL' => EventType::Live,
                default => throw new RuntimeException("Unable to determine event type {$data['eventType']}"),
            },
            'cancelled_at' => match ($data['status']) {
                'cancelled' => now(),
                default => null
            },
            'rsvp' => $data['yes_rsvp_count'],
            'service' => EventServices::MeetupRest,
            'service_id' => $data['id'],
            'venue' => $this->mapIntoVenueData($data),
        ]);
    }

    protected function mapIntoVenueData(array $data): ?VenueData
    {
        if ( ! isset($data['venue'])) {
            return null;
        }

        if ($data['venue']['name'] === 'Online event') {
            return null;
        }

        try {
            return VenueData::from([
                'id' => $data['venue']['id'],
                'name' => $data['venue']['name'],
                'address' => $data['venue']['address_1'] ?? '',
                'city' => $data['venue']['city'],
                'state' => $data['venue']['state'],
                'zip' => $data['venue']['zip'],
                'country' => $data['venue']['country'],
                'lat' => $data['venue']['lat'],
                'lon' => $data['venue']['lon'],
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }
    }

    protected function eventResults(int $page): Collection
    {
        // Meetup's pagination is a bit tricky.
        // Initially we have to specify the data we want,
        // In response, it provides a "Link" header which tells us what the next page is
        // The "Link" header data should be saved and be used for the next request
        if (null === $this->next_page_url) {
            $response = $this->initialApiCall();
        } else {
            $response = Http::get($this->next_page_url)->throw();
        }

        $this->determineNextPage($response);

        return $response->collect();
    }

    protected function determineNextPage(Response $response): void
    {
        if ($links = $response->header('Link')) {
            $link = collect($links)
                ->flatMap(fn ($data) => Str::of($data)->match('/<(.*)>; rel="next"/i')->toString())
                ->implode('');

            if (empty($link)) {
                $this->next_page_url = null;
            } else {
                $this->next_page_url = $link;
            }
        }
    }
}
