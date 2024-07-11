<?php

use App\Enums\EventServices;
use HackGreenville\EventImporter\Services\EventBriteHandler;
use HackGreenville\EventImporter\Services\LumaHandler;
use HackGreenville\EventImporter\Services\MeetupRestHandler;

return [
    'max_days_in_past' => env('EVENT_IMPORTER_MAX_DAYS_IN_PAST', 30),
    'max_days_in_future' => env('EVENT_IMPORTER_MAX_DAYS_IN_FUTURE', 180),
    'handlers' => [
        EventServices::EventBrite->value => EventBriteHandler::class,
        EventServices::MeetupRest->value => MeetupRestHandler::class,
        EventServices::Luma->value => LumaHandler::class,
    ],
    'active_services' => [
        EventServices::EventBrite->value,
        EventServices::MeetupRest->value,
        EventServices::Luma->value,
    ],
];
