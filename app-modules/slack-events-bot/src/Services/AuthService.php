<?php

namespace HackGreenville\SlackEventsBot\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthService
{
    public function getUserInfo(string $userId): array
    {
        $response = Http::withToken(config('slack-events-bot.bot_token'))
            ->get('https://slack.com/api/users.info', [
                'user' => $userId,
            ]);

        return $response->json();
    }

    public function isAdmin(string $userId): bool
    {
        $userInfo = $this->getUserInfo($userId);
        
        return $userInfo['user']['is_admin'] ?? false;
    }

    public function validateSlackRequest(Request $request): bool
    {
        $timestamp = $request->header('X-Slack-Request-Timestamp');
        $signature = $request->header('X-Slack-Signature');
        
        if (!$timestamp || !$signature) {
            return false;
        }

        // Check for possible replay attacks (5 minutes)
        if (abs(time() - intval($timestamp)) > 60 * 5) {
            return false;
        }

        // Verify signature
        $signingSecret = config('slack-events-bot.signing_secret');
        $sigBasestring = 'v0:' . $timestamp . ':' . $request->getContent();
        $mySignature = 'v0=' . hash_hmac('sha256', $sigBasestring, $signingSecret);

        return hash_equals($mySignature, $signature);
    }
}
