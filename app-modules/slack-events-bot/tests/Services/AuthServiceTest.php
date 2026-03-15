<?php

namespace HackGreenville\SlackEventsBot\Tests\Services;

use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use HackGreenville\SlackEventsBot\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery;
use RuntimeException;
use Tests\DatabaseTestCase;

class AuthServiceTest extends DatabaseTestCase
{
    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = $this->app->make(AuthService::class);
    }

    public function test_get_user_info()
    {
        $teamId = 'T123';
        $userId = 'U123';

        Http::fake([
            'https://slack.com/api/users.info*' => Http::response([
                'ok' => true,
                'user' => ['id' => $userId, 'is_admin' => true],
            ]),
        ]);

        SlackWorkspace::factory()->create([
            'team_id' => $teamId,
        ]);

        $userInfo = $this->authService->getUserInfo($userId, $teamId);

        $this->assertIsArray($userInfo);
        $this->assertTrue($userInfo['ok']);
        $this->assertEquals($userId, $userInfo['user']['id']);
    }

    public function test_get_user_info_workspace_not_found()
    {
        $teamId = 'T123';
        $userId = 'U123';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Workspace with team ID {$teamId} not found.");

        $this->authService->getUserInfo($userId, $teamId);
    }

    public function test_get_user_info_slack_api_error()
    {
        $teamId = 'T123';
        $userId = 'U123';

        Http::fake([
            'https://slack.com/api/users.info*' => Http::response([
                'ok' => false,
                'error' => 'user_not_found',
            ]),
        ]);

        SlackWorkspace::factory()->create([
            'team_id' => $teamId,
        ]);

        Log::spy();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Slack API error when fetching user info: user_not_found');

        $this->authService->getUserInfo($userId, $teamId);
    }

    public function test_is_admin_returns_true_for_admin_user()
    {
        $userId = 'U123';
        $teamId = 'T123';

        SlackWorkspace::factory()->create(['team_id' => $teamId]);

        Http::fake([
            'https://slack.com/api/users.info*' => Http::response([
                'ok' => true,
                'user' => ['is_admin' => true],
            ]),
        ]);

        $this->assertTrue($this->authService->isAdmin($userId, $teamId));
    }

    public function test_is_admin_returns_false_for_non_admin_user()
    {
        $userId = 'U123';
        $teamId = 'T123';

        SlackWorkspace::factory()->create(['team_id' => $teamId]);

        Http::fake([
            'https://slack.com/api/users.info*' => Http::response([
                'ok' => true,
                'user' => ['is_admin' => false],
            ]),
        ]);

        $this->assertFalse($this->authService->isAdmin($userId, $teamId));
    }

    public function test_is_admin_returns_false_when_is_admin_key_missing()
    {
        $userId = 'U123';
        $teamId = 'T123';

        SlackWorkspace::factory()->create(['team_id' => $teamId]);

        Http::fake([
            'https://slack.com/api/users.info*' => Http::response([
                'ok' => true,
                'user' => [],
            ]),
        ]);

        $this->assertFalse($this->authService->isAdmin($userId, $teamId));
    }

    public function test_is_admin_returns_false_when_user_key_missing()
    {
        $userId = 'U123';
        $teamId = 'T123';

        SlackWorkspace::factory()->create(['team_id' => $teamId]);

        Http::fake([
            'https://slack.com/api/users.info*' => Http::response([
                'ok' => true,
            ]),
        ]);

        $this->assertFalse($this->authService->isAdmin($userId, $teamId));
    }

    public function test_validate_slack_request_valid()
    {
        $signingSecret = 'test_signing_secret';
        config(['slack-events-bot.signing_secret' => $signingSecret]);

        $timestamp = time();
        $body = 'token=gIkuvaNzCQz2PNYwY2TBCJg2&team_id=T0001&team_domain=example&channel_id=C2147483705&channel_name=fun&user_id=U2147483697&user_name=steve&command=/weather&text=94070&response_url=https://hooks.slack.com/commands/1234/5678&trigger_id=1334522460.139236018';
        $signature = 'v0=' . hash_hmac('sha256', 'v0:' . $timestamp . ':' . $body, $signingSecret);

        $request = Request::create('/slack/events', 'POST', [], [], [], [
            'HTTP_X_SLACK_REQUEST_TIMESTAMP' => $timestamp,
            'HTTP_X_SLACK_SIGNATURE' => $signature,
        ], $body);

        $this->assertTrue($this->authService->validateSlackRequest($request));
    }

    public function test_validate_slack_request_missing_headers()
    {
        Log::spy();

        $body = 'test_body';
        $request = Request::create('/slack/events', 'POST', [], [], [], [], $body);
        $this->assertFalse($this->authService->validateSlackRequest($request));

        Log::shouldHaveReceived('warning')->with('Slack request validation failed: Missing timestamp or signature.');
    }

    public function test_validate_slack_request_expired_timestamp()
    {
        $signingSecret = 'test_signing_secret';
        config(['slack-events-bot.signing_secret' => $signingSecret]);
        Log::spy();

        $body = 'test_body';
        $timestamp = time() - (60 * 6); // 6 minutes ago
        $signature = 'v0=' . hash_hmac('sha256', 'v0:' . $timestamp . ':' . $body, $signingSecret);
        $request = Request::create('/slack/events', 'POST', [], [], [], [
            'HTTP_X_SLACK_REQUEST_TIMESTAMP' => $timestamp,
            'HTTP_X_SLACK_SIGNATURE' => $signature,
        ], $body);

        $this->assertFalse($this->authService->validateSlackRequest($request));

        Log::shouldHaveReceived('warning')->with('Slack request validation failed: Timestamp expired (replay attack?).', Mockery::any());
    }

    public function test_validate_slack_request_missing_signing_secret()
    {
        config(['slack-events-bot.signing_secret' => null]);
        Log::spy();

        $body = 'test_body';
        $timestamp = time();
        $signature = 'v0=' . hash_hmac('sha256', 'v0:' . $timestamp . ':' . $body, '');
        $request = Request::create('/slack/events', 'POST', [], [], [], [
            'HTTP_X_SLACK_REQUEST_TIMESTAMP' => $timestamp,
            'HTTP_X_SLACK_SIGNATURE' => $signature,
        ], $body);

        $this->assertFalse($this->authService->validateSlackRequest($request));

        Log::shouldHaveReceived('error')->with('Slack request validation failed: Signing secret is not configured.');
    }

    public function test_validate_slack_request_signature_mismatch()
    {
        $signingSecret = 'test_signing_secret';
        config(['slack-events-bot.signing_secret' => $signingSecret]);
        Log::spy();

        $body = 'test_body';
        $timestamp = time();
        $invalidSignature = 'v0=' . hash_hmac('sha256', 'v0:' . $timestamp . ':' . 'invalid_body', $signingSecret);
        $request = Request::create('/slack/events', 'POST', [], [], [], [
            'HTTP_X_SLACK_REQUEST_TIMESTAMP' => $timestamp,
            'HTTP_X_SLACK_SIGNATURE' => $invalidSignature,
        ], $body);

        $this->assertFalse($this->authService->validateSlackRequest($request));

        Log::shouldHaveReceived('warning')->with('Slack request validation failed: Signature mismatch.');
    }
}
