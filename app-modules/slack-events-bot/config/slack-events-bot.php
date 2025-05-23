<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Slack Bot Configuration
    |--------------------------------------------------------------------------
    |
    | These are the configuration values for the Slack Events Bot.
    |
    */

    'bot_token' => env('SLACK_BOT_TOKEN'),
    'signing_secret' => env('SLACK_SIGNING_SECRET'),
    'client_id' => env('SLACK_CLIENT_ID'),
    'client_secret' => env('SLACK_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    'scopes' => [
        'chat:write',
        'chat:write.public',
        'commands',
        'incoming-webhook',
        'users:read',
    ],

    /*
    |--------------------------------------------------------------------------
    | Message Configuration
    |--------------------------------------------------------------------------
    */
    'max_message_character_length' => 3000,
    'header_buffer_length' => 61,

    /*
    |--------------------------------------------------------------------------
    | Cooldown Configuration
    |--------------------------------------------------------------------------
    */
    'check_api_cooldown_minutes' => 15,

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    */
    'old_messages_retention_days' => 90,

    /*
    |--------------------------------------------------------------------------
    | Event Configuration
    |--------------------------------------------------------------------------
    */
    'days_to_look_back' => 1,
    'days_to_look_ahead' => 14,
];
