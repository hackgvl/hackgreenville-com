<?php

namespace HackGreenville\SlackEventsBot\Services;

use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use HackGreenville\SlackEventsBot\Services\Slack\ChatClient;
use HackGreenville\SlackEventsBot\Services\Slack\UsersClient;

class SlackApiClient
{
    public function chat(SlackChannel $channel): ChatClient
    {
        return new ChatClient($channel->workspace->access_token, $channel->slack_channel_id);
    }

    public function users(SlackWorkspace $workspace): UsersClient
    {
        return new UsersClient($workspace->access_token);
    }
}
