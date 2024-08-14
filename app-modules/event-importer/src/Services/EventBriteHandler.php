<?php

namespace HackGreenville\EventImporter\Services;

use App\Enums\EventServices;
use App\Enums\EventType;
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
            'ends_at' => Carbon::parse($data['end']['local']),
            'timezone' => Carbon::parse($data['start']['timezone']),
            // Yes "canceled" is misspelled
            'cancelled_at' => 'canceled' === $data['status'] || 'event_cancelled' === Arr::get($data, 'event_sales_status.message_code')
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
                'expand' => 'event_sales_status',
                'start_date.range_start' => now()->subDays($this->max_days_in_past)->format('Y-m-d\TH:i:s'),
                'start_date.range_end' => now()->addDays($this->max_days_in_future)->format('Y-m-d\TH:i:s'),
            ])
            ->collect()
            ->tap(function ($data) {
                $this->page_count = data_get($data, 'pagination.page_count', 1);
            })
            ->only('events')
            ->collapse()
            ->filter(fn ($data) => Arr::has($data, ['id', 'name', 'description', 'url']));
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
