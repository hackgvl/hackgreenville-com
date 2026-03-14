<?php

namespace HackGreenville\SlackEventsBot\Http\Controllers;

use HackGreenville\SlackEventsBot\Jobs\CheckEventsApi;
use HackGreenville\SlackEventsBot\Services\AuthService;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SlackController
{
    public function __construct(
        private AuthService $authService,
        private DatabaseService $databaseService
    ) {
    }

    public function install()
    {
        $state = Str::random(40);
        session(['slack_oauth_state' => $state]);

        $clientId = config('slack-events-bot.client_id');
        $scopes = implode(',', config('slack-events-bot.scopes'));

        $url = "https://slack.com/oauth/v2/authorize?" . http_build_query([
            'client_id' => $clientId,
            'scope' => $scopes,
            'state' => $state,
        ]);

        return response(view('slack-events-bot::install', ['url' => $url]));
    }

    public function auth(Request $request): Response
    {
        $validated = $request->validate([
            'error' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:512',
            'state' => 'nullable|string|max:255',
        ]);

        $error = $validated['error'] ?? null;

        if ($error) {
            return response('Something is wrong with the installation (error: ' . e($error) . ')', 400);
        }

        $code = $validated['code'] ?? null;
        $state = $validated['state'] ?? null;
        $sessionState = session()->pull('slack_oauth_state');

        if ($code && $state && $sessionState && hash_equals($sessionState, $state)) {

            $response = Http::asForm()->post('https://slack.com/api/oauth.v2.access', [
                'client_id' => config('slack-events-bot.client_id'),
                'client_secret' => config('slack-events-bot.client_secret'),
                'code' => $code,
            ]);

            $jsonResponse = $response->json();

            if ($response->successful() && ($jsonResponse['ok'] ?? false)) {
                $this->databaseService->createOrUpdateWorkspace($jsonResponse);
                return response('The HackGreenville API bot has been installed successfully!');
            }

            $error = $jsonResponse['error'] ?? 'unknown_error';

            if ($error === 'invalid_code') {
                Log::warning('Slack OAuth failed with invalid_code. This may be due to a user re-trying the auth flow or the code expiring.', [
                    'response' => $jsonResponse,
                ]);
                return response('The authorization code is invalid or has expired. Please try installing the app again at /slack/install.', 400)
                    ->header('Content-Type', 'text/plain');
            }

            Log::error('Slack OAuth failed', ['error' => $error, 'response' => $jsonResponse]);
            return response('Something is wrong with the installation. Please try again later.', 400);
        }

        return response('Invalid state. Your session may have expired or you tried to use an old authorization link. Please try installing the app again at /slack/install.', 400)
            ->header('Content-Type', 'text/plain');
    }

    public function events(Request $request): Response
    {
        $payload = $request->all();

        // Handle URL verification challenge
        if (isset($payload['type']) && $payload['type'] === 'url_verification') {
            return response($payload['challenge']);
        }

        // Handle slash commands
        if (isset($payload['command'])) {
            return $this->handleSlashCommand($payload);
        }

        // Handle other events
        if (isset($payload['event'])) {
            Log::debug('Received Slack event', ['type' => $payload['event']['type'] ?? 'unknown']);
        }

        return response('', 200);
    }

    private function handleSlashCommand(array $payload): Response
    {
        Log::info('Handling slash command', ['payload' => $payload]);

        $command = $payload['command'] ?? '';
        $userId = $payload['user_id'] ?? '';
        $channelId = $payload['channel_id'] ?? '';
        $teamId = $payload['team_id'] ?? '';

        if ( ! $userId || ! $channelId || ! $teamId) {
            Log::warning('Slash command missing required fields', ['payload' => $payload]);
            return response('Invalid request: missing required fields', 400);
        }

        $originalCommand = $command;

        // Normalize dev commands (e.g. /dev_add_channel) to match production commands
        if (str_starts_with($command, '/dev_')) {
            $command = '/' . mb_substr($command, 5);
            Log::info('Normalized dev command', ['original' => $originalCommand, 'normalized' => $command]);
        }

        switch ($command) {
            case '/add_channel':
                Log::info('Executing /add_channel command', ['user_id' => $userId, 'channel_id' => $channelId]);
                if ( ! $this->authService->isAdmin($userId, $teamId)) {
                    Log::warning('/add_channel failed: user is not an admin', ['user_id' => $userId]);
                    return response('You must be a workspace admin in order to run `/add_channel`');
                }

                $channel = $this->databaseService->addChannel($channelId, $teamId);
                if ( ! $channel->wasRecentlyCreated) {
                    Log::info('/add_channel: channel already exists', ['channel_id' => $channelId]);
                    return response('Slack events bot has already been activated for this channel');
                }
                Log::info('Successfully added channel', ['channel_id' => $channelId]);
                return response('Added channel to slack events bot 👍');

            case '/remove_channel':
                Log::info('Executing /remove_channel command', ['user_id' => $userId, 'channel_id' => $channelId]);
                if ( ! $this->authService->isAdmin($userId, $teamId)) {
                    Log::warning('/remove_channel failed: user is not an admin', ['user_id' => $userId]);
                    return response('You must be a workspace admin in order to run `/remove_channel`');
                }

                $removed = $this->databaseService->removeChannel($channelId);
                if ($removed === 0) {
                    Log::info('/remove_channel: channel not found', ['channel_id' => $channelId]);
                    return response('Slack events bot is not activated for this channel');
                }
                Log::info('Successfully removed channel', ['channel_id' => $channelId]);
                return response('Removed channel from slack events bot 👍');

            case '/check_api':
                Log::info('Executing /check_api command', ['user_id' => $userId, 'team_id' => $teamId]);
                $expiryTime = $this->databaseService->getCooldownExpiryTime($teamId, 'check_api');

                if ($expiryTime && $expiryTime->isFuture()) {
                    Log::info('/check_api command on cooldown', [
                        'team_id' => $teamId,
                        'expires_at' => $expiryTime->toIso8601String(),
                    ]);
                    return response(
                        'This command has been run recently and is on a cooldown period. ' .
                        'Please try again in a little while!'
                    );
                }

                $cooldownMinutes = config('slack-events-bot.check_api_cooldown_minutes');
                $this->databaseService->createCooldown(
                    $teamId,
                    'check_api',
                    $cooldownMinutes
                );
                Log::info('/check_api cooldown created', [
                    'team_id' => $teamId,
                    'duration_minutes' => $cooldownMinutes,
                ]);

                // Run API check asynchronously
                CheckEventsApi::dispatch();

                Log::info('/check_api command successful, dispatched job.', ['user_id' => $userId]);
                return response('Checking api for events 👍');

            default:
                Log::warning('Unknown slash command received', ['command' => $originalCommand, 'payload' => $payload]);
                return response('Unknown command', 400);
        }
    }
}
