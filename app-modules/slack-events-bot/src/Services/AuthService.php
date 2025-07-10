<?php

namespace HackGreenville\SlackEventsBot\Services;

use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AuthService
{
    public function getUserInfo(string $userId, string $teamId): array
    {
        $workspace = SlackWorkspace::where('team_id', $teamId)->first();

        if ( ! $workspace) {
            throw new RuntimeException("Workspace with team ID {$teamId} not found.");
        }

        $response = Http::withToken($workspace->access_token)
            ->get('https://slack.com/api/users.info', [
                'user' => $userId,
            ]);

        return $response->json();
    }

    public function isAdmin(string $userId, string $teamId): bool
    {
        $userInfo = $this->getUserInfo($userId, $teamId);

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
