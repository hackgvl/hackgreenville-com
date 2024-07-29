<?php

use App\Enums\EventServices;
use HackGreenville\EventImporter\Services\EventBriteHandler;
use HackGreenville\EventImporter\Services\LumaHandler;
use HackGreenville\EventImporter\Services\MeetupGraphqlHandler;
use HackGreenville\EventImporter\Services\MeetupRestHandler;

return [
    'max_days_in_past' => env('EVENT_IMPORTER_MAX_DAYS_IN_PAST', 30),
    'max_days_in_future' => env('EVENT_IMPORTER_MAX_DAYS_IN_FUTURE', 180),
    'meetup_graphql_private_key_path' => env('EVENT_IMPORTER_MEETUP_GRAPHQL_PRIVATE_KEY_PATH'),
    'meetup_graphql_client_id' => env('EVENT_IMPORTER_MEETUP_GRAPHQL_CLIENT_ID'),
    'meetup_graphql_member_id' => env('EVENT_IMPORTER_MEETUP_GRAPHQL_MEMBER_ID'),
    'meetup_graphql_private_key_id' => env('EVENT_IMPORTER_MEETUP_GRAPHQL_PRIVATE_KEY_ID'),
    'handlers' => [
        EventServices::EventBrite->value => EventBriteHandler::class,
        EventServices::MeetupRest->value => MeetupRestHandler::class,
        EventServices::MeetupGraphql->value => MeetupGraphqlHandler::class,
        EventServices::Luma->value => LumaHandler::class,
    ],
    'active_services' => [
        EventServices::EventBrite->value,
        EventServices::MeetupRest->value,
        EventServices::MeetupGraphql->value,
        EventServices::Luma->value,
    ],
];
