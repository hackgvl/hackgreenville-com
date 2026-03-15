<?php

namespace HackGreenville\EventImporter\Services;

use App\Enums\EventServices;
use Carbon\Carbon;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class LumaHandler extends AbstractEventHandler
{
    public static $LUMA_API_BASE_URL = "https://api2.luma.com";

    protected function mapIntoEventData(array $data): EventData
    {
        return EventData::from([
            'id' => $data['event']['api_id'],
            'name' => $data['event']['name'],
            'description' => $data['calendar']['description_short'] ?? '',
            'url' => "https://lu.ma/" . $data['event']['url'],
            'starts_at' => Carbon::parse($data['event']['start_at'])->setTimezone($data['event']['timezone']),
            'ends_at' => Carbon::parse($data['event']['end_at'])->setTimezone($data['event']['timezone']),
            'timezone' => $data['event']['timezone'],
            'cancelled_at' => null,
            'rsvp' => $data['guest_count'],
            'service' => EventServices::Luma,
            'service_id' => $data['event']['api_id'],
            'venue' => $this->mapIntoVenueData($data),
        ]);
    }

    protected function initialApiCall(): Response
    {
        return Http::baseUrl(LumaHandler::$LUMA_API_BASE_URL . '/calendar/')
            ->withQueryParameters([
                'calendar_api_id' => $this->org->service_api_key,
                'period' => 'future',
                'pagination_limit' => '50',
            ])
            ->throw()
            ->get("get-items");
    }

    protected function mapIntoVenueData(array $data): ?VenueData
    {
        // No Address information
        if (null === $data['event']['geo_address_info']) {
            return null;
        }

        // Virtual Event
        if (Arr::get($data['event'], 'virtual_info.has_access', false)) {
            return null;
        }

        if ( ! isset($data['event']['geo_address_info']['full_address'])) {
            Log::debug('Luma - Missing Full Address', [
                'data' => $data,
            ]);

            return null;
        }

        $parts = explode(', ', $data['event']['geo_address_info']['full_address']);

        if (count($parts) < 4) {
            Log::debug('Luma - Not enough address data', [
                'data' => $data,
            ]);

            return null;
        }

        try {
            // Handle addresses with or without a location name prefix
            if (count($parts) === 4) {
                $name = '';
                array_unshift($parts, null);
            } else {
                $name = $parts[0];
            }

            $stateZip = explode(' ', $parts[3]);

            return VenueData::from([
                'id' => $data['event']['geo_address_info']['place_id'],
                'name' => $name,
                'address' => $parts[1],
                'city' => $parts[2],
                'state' => $stateZip[0],
                'zip' => $stateZip[1],
                'country' => mb_substr($parts[4], 0, 2),
                'lon' => $data['event']['coordinate']['longitude'],
                'lat' => $data['event']['coordinate']['latitude'],
            ]);
        } catch (Throwable $exception) {
            Log::debug('Luma - Unable to parse address.', [
                'data' => $data,
            ]);

            return null;
        }
    }

    protected function eventResults(int $page): Collection
    {
        return $this->initialApiCall()
            ->collect('entries')
            ->filter(fn (array $event) => $event['platform'] === 'luma' && $event['is_manager'] === true)
            ->values();
    }
}
