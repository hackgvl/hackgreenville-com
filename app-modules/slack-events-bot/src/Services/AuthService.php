<?php

namespace HackGreenville\SlackEventsBot\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        if ( ! $timestamp || ! $signature) {
            Log::warning('Slack request validation failed: Missing timestamp or signature.');
            return false;
        }

        // Check for possible replay attacks (5 minutes)
        if (abs(time() - (int)$timestamp) > 60 * 5) {
            Log::warning('Slack request validation failed: Timestamp expired (replay attack?).', [
                'request_timestamp' => $timestamp,
            ]);
            return false;
        }

        // Verify signature
        $signingSecret = config('slack-events-bot.signing_secret');
        if (empty($signingSecret)) {
            Log::error('Slack request validation failed: Signing secret is not configured.');
            return false; // Fail safe
        }
        $sigBasestring = 'v0:' . $timestamp . ':' . $request->getContent();
        $mySignature = 'v0=' . hash_hmac('sha256', $sigBasestring, $signingSecret);

        if ( ! hash_equals($mySignature, $signature)) {
            Log::warning('Slack request validation failed: Signature mismatch.');
            return false;
        }

        return true;
    }
}
