<?php

namespace HackGreenville\SlackEventsBot\Http\Controllers;

use Exception;
use HackGreenville\SlackEventsBot\Jobs\CheckEventsApi;
use HackGreenville\SlackEventsBot\Services\AuthService;
use HackGreenville\SlackEventsBot\Services\BotService;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlackController
{
    public function __construct(
        private AuthService $authService,
        private BotService $botService,
        private DatabaseService $databaseService
    ) {
    }

    public function install(): Response
    {
        $state = uniqid('slack_', true);
        session(['slack_oauth_state' => $state]);

        $clientId = config('slack-events-bot.client_id');
        $scopes = implode(',', config('slack-events-bot.scopes'));

        $url = "https://slack.com/oauth/v2/authorize?" . http_build_query([
            'client_id' => $clientId,
            'scope' => $scopes,
            'state' => $state,
        ]);

        $html = <<<HTML
        <a href="{$url}">
            <img alt="Add to Slack" height="40" width="139"
                 src="https://platform.slack-edge.com/img/add_to_slack.png"
                 srcset="https://platform.slack-edge.com/img/add_to_slack.png 1x,
                         https://platform.slack-edge.com/img/add_to_slack@2x.png 2x"
            />
        </a>
        HTML;

        return response($html);
    }

    public function auth(Request $request): Response
    {
        $code = $request->get('code');
        $state = $request->get('state');
        $error = $request->get('error');

        if ($error) {
            return response("Something is wrong with the installation (error: {$error})", 400);
        }

        $sessionState = session('slack_oauth_state');

        if ($code && $sessionState === $state) {
            session()->forget('slack_oauth_state');

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
                return response('The authorization code is invalid or has expired. Please <a href="/slack/install">try installing the app again</a>.', 400);
            }

            Log::error('Slack OAuth failed', ['error' => $error, 'response' => $jsonResponse]);
            return response("Something is wrong with the installation (error: {$error})", 400);
        }

        return response('Invalid state. Your session may have expired or you tried to use an old authorization link. Please <a href="/slack/install">try installing the app again</a>.', 400);
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
            // Process events asynchronously if needed
            Log::info('Received Slack event', $payload);
        }

        return response('', 200);
    }

    private function handleSlashCommand(array $payload): Response
    {
        Log::info('Handling slash command', ['payload' => $payload]);

        $command = $payload['command'];
        $userId = $payload['user_id'];
        $channelId = $payload['channel_id'];
        $teamDomain = $payload['team_domain'] ?? null;

        $originalCommand = $command;

        // Normalize dev commands to match production commands
        // if (str_starts_with($command, '/dev_')) {
        //     $command = str_replace('/dev_', '/', $command);
        //     Log::info('Normalized dev command', ['original' => $originalCommand, 'normalized' => $command]);
        // }

        switch ($command) {
            case '/add_channel':
                Log::info('Executing /add_channel command', ['user_id' => $userId, 'channel_id' => $channelId]);
                if ( ! $this->authService->isAdmin($userId)) {
                    Log::warning('/add_channel failed: user is not an admin', ['user_id' => $userId]);
                    return response('You must be a workspace admin in order to run `/add_channel`');
                }

                try {
                    $this->databaseService->addChannel($channelId);
                    Log::info('Successfully added channel', ['channel_id' => $channelId]);
                    return response('Added channel to slack events bot 👍');
                } catch (Exception $e) {
                    Log::error('/add_channel failed: could not add channel', [
                        'channel_id' => $channelId,
                        'exception' => $e->getMessage(),
                    ]);
                    return response('Slack events bot has already been activated for this channel');
                }

            case '/remove_channel':
                Log::info('Executing /remove_channel command', ['user_id' => $userId, 'channel_id' => $channelId]);
                if ( ! $this->authService->isAdmin($userId)) {
                    Log::warning('/remove_channel failed: user is not an admin', ['user_id' => $userId]);
                    return response('You must be a workspace admin in order to run `/remove_channel`');
                }

                try {
                    $this->databaseService->removeChannel($channelId);
                    Log::info('Successfully removed channel', ['channel_id' => $channelId]);
                    return response('Removed channel from slack events bot 👍');
                } catch (Exception $e) {
                    Log::error('/remove_channel failed: could not remove channel', [
                        'channel_id' => $channelId,
                        'exception' => $e->getMessage(),
                    ]);
                    return response('Slack events bot is not activated for this channel');
                }

            case '/check_api':
                Log::info('Executing /check_api command', ['user_id' => $userId, 'team_domain' => $teamDomain]);
                // Check cooldown
                if ($teamDomain) {
                    $expiryTime = $this->databaseService->getCooldownExpiryTime($teamDomain, 'check_api');

                    if ($expiryTime && $expiryTime->isFuture()) {
                        Log::info('/check_api command on cooldown', [
                            'team_domain' => $teamDomain,
                            'expires_at' => $expiryTime->toIso8601String(),
                        ]);
                        return response(
                            'This command has been run recently and is on a cooldown period. ' .
                            'Please try again in a little while!'
                        );
                    }

                    $cooldownMinutes = config('slack-events-bot.check_api_cooldown_minutes');
                    // Set new cooldown
                    $this->databaseService->createCooldown(
                        $teamDomain,
                        'check_api',
                        $cooldownMinutes
                    );
                    Log::info('/check_api cooldown created', [
                        'team_domain' => $teamDomain,
                        'duration_minutes' => $cooldownMinutes,
                    ]);
                }

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
