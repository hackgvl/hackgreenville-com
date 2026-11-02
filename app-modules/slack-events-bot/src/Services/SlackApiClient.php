<?php

namespace HackGreenville\SlackEventsBot\Services;

use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use HackGreenville\SlackEventsBot\Services\Slack\ChatClient;
use HackGreenville\SlackEventsBot\Services\Slack\UsersClient;
use RuntimeException;

class SlackApiClient
{
    public function chat(SlackChannel $channel): ChatClient
    {
        return new ChatClient($this->token($channel->workspace), $channel->slack_channel_id);
    }

    public function users(SlackWorkspace $workspace): UsersClient
    {
        return new UsersClient($this->token($workspace));
    }

    private function token(?SlackWorkspace $workspace): string
    {
        $token = $workspace?->access_token;

        if ( ! is_string($token) || $token === '') {
            throw new RuntimeException('Slack workspace access token is missing. Reinstall the Slack app.');
        }

        return $token;
    }
}
