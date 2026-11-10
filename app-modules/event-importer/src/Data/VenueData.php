<?php

namespace HackGreenville\EventImporter\Data;

use App\Models\Venue;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

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
    ) {
    }

    /**
     * Reuse a venue with this name, or with the slug derived from this name.
     *
     * Address and coordinates are stored only when creating a venue. Matching
     * on the full address created a new row whenever lat/lng or street
     * formatting drifted. A custom admin slug still matches through the name.
     *
     * A blank name is not an identity. Luma leaves the place name empty when
     * the organizer enters only a street, and matching that empty name reused
     * the oldest unnamed venue. The street is used as the name instead.
     */
    public function resolveVenue(): Venue
    {
        $name = $this->name;

        if (mb_trim($name) === '') {
            $name = mb_trim((string) $this->address);
        }

        $slug = Venue::normalizeSlug(null, $name);

        $existing = null;

        if ($name !== '') {
            $existing = Venue::query()->where('name', $name)->orderBy('id')->first();
        }

        if ( ! $existing && $slug !== null) {
            $existing = Venue::query()->where('slug', $slug)->orderBy('id')->first();
        }

        if ($existing) {
            return $existing;
        }

        return Venue::create([
            'name' => $name,
            'slug' => $slug,
            'address' => $this->address,
            'zipcode' => $this->zip,
            'city' => $this->city,
            'country' => $this->country,
            'state' => $this->state,
            'lat' => $this->lat,
            'lng' => $this->lon,
        ]);
    }

}
