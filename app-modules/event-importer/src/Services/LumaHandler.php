<?php

namespace HackGreenville\EventImporter\Services;

use App\Enums\EventServices;
use Carbon\Carbon;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Http\Client\Response;
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
        if (null === $data['event']['geo_address_info']) {
            return null;
        }

        $info = $data['event']['geo_address_info'];

        if ( ! isset($info['full_address']) || mb_trim((string) $info['full_address']) === '') {
            Log::debug('Luma - Missing Full Address', [
                'data' => $data,
            ]);

            return null;
        }

        $parsed = $this->venueFromGeoAddressInfo($info);

        if ($parsed === null) {
            Log::debug('Luma - Not enough address data', [
                'data' => $data,
            ]);

            return null;
        }

        try {
            return VenueData::from([
                'id' => (string) ($info['place_id'] ?? ''),
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
     * Map Luma's parsed geo_address_info onto a venue.
     *
     * address is the place name, or the street when the organizer did not
     * enter a name. short_address is the street plus a trailing city. Zip is
     * only present in full_address. Older events omit short_address,
     * region_short, or country_code, and those values fall back to full_address.
     *
     * @param  array<string, mixed>  $info
     * @return array{name: string, address: ?string, city: string, state: string, zip: ?string, country: string}|null
     */
    private function venueFromGeoAddressInfo(array $info): ?array
    {
        $fullAddress = (string) $info['full_address'];
        $city = mb_trim((string) ($info['city'] ?? ''));
        $name = mb_trim((string) ($info['address'] ?? ''));
        $tail = $this->parseFullAddressTail($fullAddress);

        $state = mb_trim((string) ($info['region_short'] ?? ''));

        if ($state === '') {
            $state = $tail['state'];
        }

        $country = mb_trim((string) ($info['country_code'] ?? ''));

        if ($country === '') {
            $country = $tail['country'];
        }

        $street = $this->streetFromGeoAddress($info, $city, $name, $fullAddress);

        if ($name === '') {
            $name = $street ?? '';
        }

        if ($name === '' || $city === '' || $state === '') {
            return null;
        }

        return [
            'name' => $name,
            'address' => $street,
            'city' => $city,
            'state' => $state,
            'zip' => $tail['zip'],
            'country' => $country !== '' ? $country : 'US',
        ];
    }

    /**
     * @param  array<string, mixed>  $info
     */
    private function streetFromGeoAddress(array $info, string $city, string $venueName, string $fullAddress): ?string
    {
        $short = mb_trim((string) ($info['short_address'] ?? ''));

        if ($short !== '') {
            return $this->stripTrailingCity($short, $city);
        }

        return $this->streetFromFullAddress($fullAddress, $venueName, $city);
    }

    private function stripTrailingCity(string $short, string $city): ?string
    {
        if ($city !== '') {
            $suffix = ', ' . $city;

            if (str_ends_with($short, $suffix)) {
                $short = mb_trim(mb_substr($short, 0, mb_strlen($short) - mb_strlen($suffix)));
            } elseif (mb_strtolower($short) === mb_strtolower($city)) {
                return null;
            }
        }

        return $short === '' ? null : $short;
    }

    private function streetFromFullAddress(string $fullAddress, string $venueName, string $city): ?string
    {
        $parts = explode(', ', $fullAddress);

        if (count($parts) < 3) {
            return null;
        }

        array_pop($parts);
        array_pop($parts);

        if ($city !== '' && $parts !== [] && end($parts) === $city) {
            array_pop($parts);
        }

        if ($parts === []) {
            return null;
        }

        if (count($parts) > 1 && $parts[0] === $venueName) {
            array_shift($parts);
        }

        $street = implode(', ', $parts);

        if ($street === '' || ($city !== '' && mb_strtolower($street) === mb_strtolower($city))) {
            return null;
        }

        return $street;
    }

    /**
     * @return array{state: string, zip: ?string, country: string}
     */
    private function parseFullAddressTail(string $fullAddress): array
    {
        $parts = explode(', ', $fullAddress);
        $empty = ['state' => '', 'zip' => null, 'country' => ''];

        if (count($parts) < 2) {
            return $empty;
        }

        $countryToken = (string) array_pop($parts);
        $stateZip = explode(' ', (string) array_pop($parts));
        $country = match (mb_strtoupper($countryToken)) {
            'USA', 'US' => 'US',
            default => mb_substr($countryToken, 0, 2),
        };

        return [
            'state' => $stateZip[0] ?? '',
            'zip' => $stateZip[1] ?? null,
            'country' => $country,
        ];
    }
}
