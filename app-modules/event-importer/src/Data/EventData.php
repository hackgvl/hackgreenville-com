<?php

namespace HackGreenville\EventImporter\Data;

use App\Enums\EventServices;
use App\Enums\EventType;
use App\Traits\HasUniqueIdentifier;
use Carbon\Carbon;
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
        public EventType      $event_type,
        public EventServices  $service,
        public string         $service_id,
        public null|VenueData $venue,
    ) {
    }

    public function hasVenue(): bool
    {
        return $this->venue instanceof VenueData;
    }
}
