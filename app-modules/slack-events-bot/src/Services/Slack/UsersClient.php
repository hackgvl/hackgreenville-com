<?php

namespace HackGreenville\SlackEventsBot\Services\Slack;

use Illuminate\Support\Facades\Http;

class UsersClient extends BaseClient
{
    public function info(string $userId): array
    {
        $response = Http::withToken($this->token)
            ->get('https://slack.com/api/users.info', [
                'user' => $userId,
            ]);

        return $this->validateResponse($response, 'fetching user info');
    }
}
