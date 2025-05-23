<?php

namespace HackGreenville\SlackEventsBot\Http\Controllers;

use Exception;
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

        if ($code && session('slack_oauth_state') === $state) {
            session()->forget('slack_oauth_state');

            $response = Http::post('https://slack.com/api/oauth.v2.access', [
                'client_id' => config('slack-events-bot.client_id'),
                'client_secret' => config('slack-events-bot.client_secret'),
                'code' => $code,
            ]);

            if ($response->successful()) {
                return response('The HackGreenville API bot has been installed successfully!');
            }
        }

        return response('Something is wrong with the installation', 400);
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
        $command = $payload['command'];
        $userId = $payload['user_id'];
        $channelId = $payload['channel_id'];
        $teamDomain = $payload['team_domain'] ?? null;

        switch ($command) {
            case '/add_channel':
                if ( ! $this->authService->isAdmin($userId)) {
                    return response('You must be a workspace admin in order to run `/add_channel`');
                }

                try {
                    $this->databaseService->addChannel($channelId);
                    return response('Added channel to slack events bot 👍');
                } catch (Exception $e) {
                    return response('Slack events bot has already been activated for this channel');
                }

            case '/remove_channel':
                if ( ! $this->authService->isAdmin($userId)) {
                    return response('You must be a workspace admin in order to run `/remove_channel`');
                }

                try {
                    $this->databaseService->removeChannel($channelId);
                    return response('Removed channel from slack events bot 👍');
                } catch (Exception $e) {
                    return response('Slack events bot is not activated for this channel');
                }

            case '/check_api':
                // Check cooldown
                if ($teamDomain) {
                    $expiryTime = $this->databaseService->getCooldownExpiryTime($teamDomain, 'check_api');

                    if ($expiryTime && $expiryTime->isFuture()) {
                        return response(
                            'This command has been run recently and is on a cooldown period. ' .
                            'Please try again in a little while!'
                        );
                    }

                    // Set new cooldown
                    $this->databaseService->createCooldown(
                        $teamDomain,
                        'check_api',
                        config('slack-events-bot.check_api_cooldown_minutes')
                    );
                }

                // Run API check asynchronously
                dispatch(function () {
                    $this->botService->checkApi();
                })->afterResponse();

                return response('Checking api for events 👍');

            default:
                return response('Unknown command', 400);
        }
    }
}
