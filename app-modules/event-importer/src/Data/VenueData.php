<?php

namespace HackGreenville\EventImporter\Data;

use App\Models\State;
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

    public function resolveVenue(EventData $data): Venue
    {
        return Venue::updateOrCreate([
            'name' => $this->name,
            'address' => $this->address,
            'zipcode' => $this->zip,
            'city' => $this->city,
            'country' => $this->country,
            'state_id' => $this->resolveState()->id,
            'lat' => $this->lat,
            'lng' => $this->lon,
        ]);
    }

    protected function resolveState(): State
    {
        return State::firstOrCreate([
            'abbr' => $this->state,
        ], [
            'name' => $this->state,
        ]);
    }
}
