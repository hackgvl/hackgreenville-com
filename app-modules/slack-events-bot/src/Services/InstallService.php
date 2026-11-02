<?php

namespace HackGreenville\SlackEventsBot\Services;

use Illuminate\Support\Str;

class InstallService
{
    public function authorizationUrl(): string
    {
        $state = Str::random(40);
        session(['slack_oauth_state' => $state]);

        return 'https://slack.com/oauth/v2/authorize?' . http_build_query([
            'client_id' => config('slack-events-bot.client_id'),
            'scope' => implode(',', config('slack-events-bot.scopes')),
            'state' => $state,
        ]);
    }
}
