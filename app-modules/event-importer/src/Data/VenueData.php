<?php

namespace HackGreenville\EventImporter\Data;

use App\Models\Venue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;
use Throwable;

class VenueData extends Data
{
    public function __construct(
        public string  $id,
        public string  $name,
        public ?string $address,
        public ?string $city,
        #[Computed]
        public ?string $state,
        public ?string $zip,
        public ?float  $lat = 0,
        public ?float  $lon = 0,
        public ?string $country = 'US',
        public ?string $googlePlaceId = null,
        public ?string $appleMapsPlaceId = null,
    ) {
    }

    /**
     * Reuse a venue with this name, or with the slug derived from this name.
     *
     * Address and coordinates are stored only when creating a venue. Matching
     * on the full address created a new row whenever lat/lng or street
     * formatting drifted. A custom admin slug still matches through the name.
     *
     * Luma place ids are checked after the name. The same building can come
     * back with a different place id, so an id match only collapses the cases
     * where Luma repeats an id. An existing row keeps the place id it already
     * has. A missing id is stored when no other venue already has it.
     *
     * A blank name is not a match key when a place id is present. Luma
     * address-only events use an empty name, and matching that would attach
     * the place id to the oldest unnamed venue.
     * A newly created venue is written to the log.
     */
    public function resolveVenue(): Venue
    {
        $slug = Venue::normalizeSlug(null, $this->name);
        $googlePlaceId = $this->normalizedPlaceId($this->googlePlaceId);
        $appleMapsPlaceId = $this->normalizedPlaceId($this->appleMapsPlaceId);
        $nameIsBlank = mb_trim($this->name) === '';

        $existing = null;

        if ( ! $nameIsBlank || ($googlePlaceId === null && $appleMapsPlaceId === null)) {
            $existing = Venue::query()->where('name', $this->name)->orderBy('id')->first();

            if ( ! $existing && $slug !== null) {
                $existing = Venue::query()->where('slug', $slug)->orderBy('id')->first();
            }
        }

        if ( ! $existing) {
            $existing = $this->venueWithPlaceId('google_place_id', $googlePlaceId);
        }

        if ( ! $existing) {
            $existing = $this->venueWithPlaceId('apple_maps_place_id', $appleMapsPlaceId);
        }

        if ($existing) {
            if ( ! $nameIsBlank) {
                $this->rememberMissingPlaceIds($existing);
            }

            return $existing;
        }

        $venue = Venue::create([
            'name' => $this->name,
            'slug' => $slug,
            'address' => $this->address,
            'zipcode' => $this->zip,
            'city' => $this->city,
            'country' => $this->country,
            'state' => $this->state,
            'lat' => $this->lat,
            'lng' => $this->lon,
            'google_place_id' => $googlePlaceId,
            'apple_maps_place_id' => $appleMapsPlaceId,
        ]);

        $this->logNewVenue($venue);

        return $venue;
    }

    private function rememberMissingPlaceIds(Venue $venue): void
    {
        $updates = [];

        foreach ([
            'google_place_id' => $this->normalizedPlaceId($this->googlePlaceId),
            'apple_maps_place_id' => $this->normalizedPlaceId($this->appleMapsPlaceId),
        ] as $column => $placeId) {
            if ($placeId === null || filled($venue->{$column})) {
                continue;
            }

            $alreadyStored = Venue::query()->where($column, $placeId)->exists();

            if ($alreadyStored) {
                continue;
            }

            $updates[$column] = $placeId;
        }

        if ($updates === []) {
            return;
        }

        $venue->fill($updates)->save();
    }

    private function logNewVenue(Venue $venue): void
    {
        $context = [
            'venue_id' => $venue->id,
            'name' => $venue->name,
            'address' => $venue->address,
            'city' => $venue->city,
            'state' => $venue->state,
            'zipcode' => $venue->zipcode,
            'google_place_id' => $venue->google_place_id,
            'apple_maps_place_id' => $venue->apple_maps_place_id,
        ];

        Log::info('Event import created a venue', $context);

        $url = config('logging.channels.slack.url');

        if (blank($url)) {
            return;
        }

        // Http with a short timeout, not the Monolog Slack handler. That
        // handler retries five times with no timeout and would stall the import.
        try {
            Http::timeout(3)
                ->connectTimeout(2)
                ->post($url, [
                    'username' => 'Venue Import',
                    'icon_emoji' => ':round_pushpin:',
                    'text' => $this->debuggingChannelText($context),
                ]);
        } catch (Throwable $exception) {
            Log::warning('Could not post a new venue to the debugging channel', [
                'venue_id' => $context['venue_id'] ?? null,
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    private function debuggingChannelText(array $context): string
    {
        $lines = ['Event import created a venue'];

        foreach ($context as $key => $value) {
            $lines[] = $key . ': ' . (string) $value;
        }

        return implode("\n", $lines);
    }

    private function venueWithPlaceId(string $column, ?string $placeId): ?Venue
    {
        $placeId = $this->normalizedPlaceId($placeId);

        if ($placeId === null) {
            return null;
        }

        return Venue::query()->where($column, $placeId)->orderBy('id')->first();
    }

    private function normalizedPlaceId(?string $placeId): ?string
    {
        $placeId = mb_trim((string) $placeId);

        return $placeId === '' ? null : $placeId;
    }

}
