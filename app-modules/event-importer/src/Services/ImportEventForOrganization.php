<?php

namespace HackGreenville\EventImporter\Services;

use App\Models\Event;
use App\Models\Org;
use HackGreenville\EventImporter\Data\EventData;
use Illuminate\Support\Facades\DB;

class ImportEventForOrganization
{
    public static function process(EventData $data, Org $org): void
    {
        DB::transaction(function () use ($data, $org) {
            Event::updateOrCreate(
                attributes: $data->uniqueIdentifier(),
                values: [
                    'event_name' => $data->name,
                    'group_name' => $org->title,
                    'description' => $data->description,
                    'rsvp_count' => $data->rsvp,
                    'active_at' => $data->starts_at,
                    'expire_at' => $data->ends_at,
                    'cancelled_at' => $data->cancelled_at,
                    'uri' => $data->url,
                    'venue_id' => $data->hasVenue()
                        ? $data->venue->resolveVenue($data)->id
                        : null,
                    'event_uuid' => $data->uniqueIdentifierHash(),
                    'organization_id' => $org->id,
                ]
            );
        });
    }
}
