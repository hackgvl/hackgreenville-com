<?php

namespace HackGreenville\SlackEventsBot\Services;

use HackGreenville\SlackEventsBot\Services\Slack\ChatClient;
use HackGreenville\SlackEventsBot\Services\Slack\UsersClient;
use RuntimeException;

class SlackApiClient
{
    private ?string $token = null;

    public function withToken(string $token): static
    {
        $clone = clone $this;
        $clone->token = $token;

        return $clone;
    }

    public function chat(): ChatClient
    {
        return new ChatClient($this->requireToken());
    }

    public function users(): UsersClient
    {
        return new UsersClient($this->requireToken());
    }

    private function requireToken(): string
    {
        if ( ! $this->token) {
            throw new RuntimeException('Token must be set via withToken() before making API calls.');
        }

        return $this->token;
    }
}
