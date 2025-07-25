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
        return Http::baseUrl('https://api.lu.ma/calendar/')
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
            $address = $this->parseGoogleAddress($parts);

        } catch (Throwable $exception) {
            Log::debug('Luma - Unable to parse address.', [
                'data' => $data,
            ]);

            return null;
        }
        return VenueData::from([
            'id' => $data['event']['geo_address_info']['place_id'],
            'name' => $address->name,
            'address' => $address->address1,
            'city' => $address->city,
            'state' => $address->state,
            'zip' => $address->zipcode,
            'country' => str_limit($address->country, 2, null),
            'lon' => $data['event']['coordinate']['longitude'],
            'lat' => $data['event']['coordinate']['latitude'],
        ]);

    }

    protected function eventResults(int $page): Collection
    {
        return $this->initialApiCall()->collect('entries');
    }

    protected function parseGoogleAddress(array $parts)
    {
        return new class ($parts) {
            public string $name;

            public ?string $address1;

            public ?string $city;

            public ?string $state;

            public ?string $zipcode;

            public ?string $country;

            public function __construct(array $parts)
            {
                // No "Location Name"
                if (count($parts) === 4) {
                    $this->name = '';
                    array_unshift($parts, null);
                } else {
                    $this->name = $parts[0];
                }

                $state_zip = explode(' ', $parts[3]);

                $state = $state_zip[0];
                $zip = $state_zip[1];

                $this->address1 = $parts[1];
                $this->city = $parts[2];
                $this->state = $state;
                $this->zipcode = $zip;
                $this->country = $parts[4];
            }
        };
    }
}
