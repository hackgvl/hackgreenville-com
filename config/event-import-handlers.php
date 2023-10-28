<?php

use App\Enums\EventServices;
use HackGreenville\EventImporter\Services\EventBriteHandler;
use HackGreenville\EventImporter\Services\MeetupRestHandler;

return [
    'handlers' => [
        EventServices::EventBrite->value => EventBriteHandler::class,
        EventServices::MeetupRest->value => MeetupRestHandler::class,
    ],
    'active_services' => [
        EventServices::EventBrite->value,
        EventServices::MeetupRest->value,
    ],
];
