<?php

namespace HackGreenville\EventImporter\Services;

use App\Enums\EventServices;
use App\Enums\EventType;
use Brick\Schema\Interfaces\EducationEvent;
use Brick\Schema\Interfaces\SocialEvent;
use Brick\Schema\SchemaReader;
use Carbon\Carbon;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class EventBriteHandler extends AbstractEventHandler
{
    protected function mapIntoEventData(array $data): EventData
    {
        return EventData::from([
            'service' => EventServices::EventBrite,
            'service_id' => $data['id'],

            'name' => $data['name']['text'],
            'description' => $data['description']['text'],
            'url' => $data['url'],
            'starts_at' => Carbon::parse($data['start']['local']),
            // Yes "canceled" is misspelled
            'cancelled_at' => 'canceled' === $data['status']
                ? now()
                : null,
            'event_type' => match ($data['online_event']) {
                true => EventType::Online,
                false => EventType::Live,
                default => throw new RuntimeException("Unable to determine event type {$data['eventType']}"),
            },
            'venue' => $this->mapIntoVenueData($data),
        ]);
    }

    protected function mapIntoVenueData(array $data): ?VenueData
    {
        if ( ! isset($data['venue_id'])) {
            return null;
        }

        $venue_id = $data['venue_id'];

        // Cache to prevent unnecessary api calls for same venue id
        $venue = Cache::remember(__CLASS__ . __FUNCTION__ . $venue_id, now()->addHour(), function () use ($venue_id) {
            return $this->client()
                ->get("v3/venues/{$venue_id}")
                ->object();
        });

        return VenueData::from([
            'id' => $venue->id,
            'name' => $venue->name,
            'address' => $venue->address->address_1,
            'city' => $venue->address->city,
            'state' => $venue->address->region,
            'zip' => $venue->address->postal_code,
            'country' => $venue->address->country,
            'lat' => $venue->latitude,
            'lon' => $venue->longitude,
        ]);
    }

    protected function eventResults(int $page): Collection
    {
        return $this->client()
            ->get("v3/organizers/{$this->org->service_api_key}/events/", [
                'status' => 'all',
                'order_by' => 'start_desc',
                'start_date.range_start' => now()->subMonths(1)->format('Y-m-d\TH:i:s'),
                'start_date.range_end' => now()->addMonths(3)->format('Y-m-d\TH:i:s'),
            ])
            ->collect()
            ->tap(function ($data) {
                $this->page_count = data_get($data, 'pagination.page_count', 1);
            })
            ->only('events')
            ->collapse()
            ->filter(fn ($data) => Arr::has($data, ['id', 'name', 'description', 'url']))
            ->map(function ($data) {

                // If the event is already over, we no longer need to look into the cancellation status
                if (Carbon::parse($data['start']['local'])->isPast()) {
                    return $data;
                }

                // Eventbrite API does not show events which were "cancelled"
                // We need to visit the event page, and pull json-ld data from the page.
                $response = Http::get($data['url'])
                    ->body();

                $schemaReader = SchemaReader::forJsonLd();
                $things = $schemaReader->readHtml($response, $data['url']);

                /** @var SocialEvent|EducationEvent $social_event */
                $social_event = Arr::first($things, fn ($thing) => $thing instanceof SocialEvent || $thing instanceof EducationEvent);

                return [
                    ...$data,
                    'status' => $social_event->eventStatus->toString() === 'https://schema.org/EventCancelled'
                        ? 'canceled'
                        : 'upcoming',
                ];
            });
    }

    protected function client()
    {
        $token = config('services.eventbrite.private_token');

        if (empty($token)) {
            throw new RuntimeException('Missing EventBright Private Token');
        }

        return Http::baseUrl('https://www.eventbriteapi.com/')
            ->throw()
            ->timeout(180)
            ->withQueryParameters([
                'token' => config('services.eventbrite.private_token'),
            ]);
    }
}
