<?php

namespace HackGreenville\EventImporter\Data;

use App\Enums\EventServices;
use App\Traits\HasUniqueIdentifier;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Timezone;
use Spatie\LaravelData\Data;

class EventData extends Data
{
    use HasUniqueIdentifier;

    public function __construct(
        public string         $name,
        public string         $description,
        public string         $url,
        public ?int           $rsvp,
        public Carbon         $starts_at,
        public Carbon         $ends_at,
        public null|Carbon    $cancelled_at,
        #[Timezone()]
        public string         $timezone,
        public EventServices  $service,
        public string         $service_id,
        public null|VenueData $venue,
        public null|bool           $is_paid,
    ) {
    }

    public function hasVenue(): bool
    {
        return $this->venue instanceof VenueData;
    }
}
