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

        $parsed = $this->parseLumaFullAddress($data['event']['geo_address_info']['full_address']);

        if ($parsed === null) {
            Log::debug('Luma - Not enough address data', [
                'data' => $data,
            ]);

            return null;
        }

        try {
            return VenueData::from([
                'id' => $data['event']['geo_address_info']['place_id'],
                'name' => $parsed['name'],
                'address' => $parsed['address'],
                'city' => $parsed['city'],
                'state' => $parsed['state'],
                'zip' => $parsed['zip'],
                'country' => $parsed['country'],
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

    /**
     * Split a Google-style Luma address.
     *
     * The tail is always city, state zip, country. A place name is present
     * only when the organizer entered one, in which case it is the first
     * component and any lines between it and the city belong to the street.
     * With no place name, the street is used as the venue name.
     *
     * @return array{name: string, address: string, city: string, state: string, zip: ?string, country: string}|null
     */
    private function parseLumaFullAddress(string $fullAddress): ?array
    {
        $parts = explode(', ', $fullAddress);

        if (count($parts) < 4) {
            return null;
        }

        $country = mb_substr((string) array_pop($parts), 0, 2);
        $stateZip = explode(' ', (string) array_pop($parts));
        $city = (string) array_pop($parts);
        $state = $stateZip[0] ?? '';
        $zip = $stateZip[1] ?? null;

        if ($city === '' || $state === '' || $parts === []) {
            return null;
        }

        if (count($parts) === 1) {
            $name = $parts[0];
            $address = $parts[0];
        } else {
            $name = (string) array_shift($parts);
            $address = implode(', ', $parts);
        }

        if (mb_trim($name) === '' || mb_trim($address) === '') {
            return null;
        }

        return [
            'name' => $name,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'country' => $country,
        ];
    }
}
