<?php

namespace App\Data;

use App\Models\State;
use App\Models\Venue;
use Carbon\Carbon;
use DateTime;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

// Coerces the config expression into a constant so that it can
// be used in attributes.
define('EVENTS_TIMEZONE', config('app.timezone'));

class EventDataTransformer extends Data
{
    public function __construct(
        public string     $service,
        public string     $service_id,
        public string     $uuid,
        public string     $event_name,
        public string     $group_name,
        public string     $description,
        public string     $status,
        #[WithCast(DateTimeInterfaceCast::class, setTimeZone: EVENTS_TIMEZONE)]
        public DateTime   $time,
        public ?int       $rsvp_count = 0,
        #[Computed]
        public ?string    $url = null,
        public ?VenueData $venue = null,
    ) {
        $this->url = $this->url ?: '#no-url';
    }

    public function getCancelledAtOrNull(): ?Carbon
    {
        return $this->status === 'cancelled'
            ? new Carbon
            : null;
    }

    public function uniqueIdentifier(): array
    {
        return [
            'service' => $this->service,
            'service_id' => $this->service_id,
        ];
    }

    public function hasVenue(): bool
    {
        return $this->venue instanceof VenueData;
    }

    public function resolveVenue(): Venue
    {
        return Venue::updateOrCreate(
            attributes: [
                'address' => $this->venue->address,
                'zipcode' => $this->venue->zip,
                'state_id' => $this->resolveState()->id,
            ],
            values: [
                'name' => $this->venue->name,
                'address' => $this->venue->address,
                'zipcode' => $this->venue->zip,
                'city' => $this->venue->city,
                'lat' => $this->venue->lat,
                'lng' => $this->venue->lon,
            ],
        );
    }

    protected function resolveState(): State
    {
        return State::firstOrCreate([
            'abbr' => $this->venue->state,
            'name' => $this->venue->state,
        ]);
    }
}
