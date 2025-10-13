<?php

namespace HackGreenville\SlackEventsBot\Tests\Services;

use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use HackGreenville\SlackEventsBot\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = $this->app->make(AuthService::class);
    }

    /**
     * Test getUserInfo method.
     */
    public function test_get_user_info()
    {
        $teamId = 'T123';
        $userId = 'U123';
        $accessToken = 'xoxb-test-token';

        // Mock Http facade
        Http::shouldReceive('withToken')
            ->with($accessToken)
            ->andReturnSelf();

        Http::shouldReceive('get')
            ->with('https://slack.com/api/users.info', ['user' => $userId])
            ->andReturn(new \Illuminate\Http\Client\Response(new \GuzzleHttp\Psr7\Response(200, [], json_encode(['ok' => true, 'user' => ['id' => $userId, 'is_admin' => true]]))));

        SlackWorkspace::factory()->create([
            'team_id' => $teamId,
            'access_token' => $accessToken,
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
        $accessToken = 'xoxb-test-token';

        // Mock Http facade to return an error
        Http::shouldReceive('withToken')
            ->with($accessToken)
            ->andReturnSelf();

        Http::shouldReceive('get')
            ->with('https://slack.com/api/users.info', ['user' => $userId])
            ->andReturn(new \Illuminate\Http\Client\Response(new \GuzzleHttp\Psr7\Response(200, [], json_encode(['ok' => false, 'error' => 'user_not_found']))));

        SlackWorkspace::factory()->create([
            'team_id' => $teamId,
            'access_token' => $accessToken,
        ]);

        $userInfo = $this->authService->getUserInfo($userId, $teamId);

        $this->assertIsArray($userInfo);
        $this->assertFalse($userInfo['ok']);
        $this->assertEquals('user_not_found', $userInfo['error']);
    }

    /**
     * Test isAdmin method.
     */
    public function test_is_admin()
    {
        $userId = 'U123';
        $teamId = 'T123';

        // Test case: User is admin
        $this->authService = Mockery::mock(AuthService::class . '[getUserInfo]');
        $this->authService->shouldReceive('getUserInfo')
            ->once()
            ->with($userId, $teamId)
            ->andReturn(['ok' => true, 'user' => ['is_admin' => true]]);

        $this->assertTrue($this->authService->isAdmin($userId, $teamId));

        // Test case: User is not admin
        $this->authService = Mockery::mock(AuthService::class . '[getUserInfo]');
        $this->authService->shouldReceive('getUserInfo')
            ->once()
            ->with($userId, $teamId)
            ->andReturn(['ok' => true, 'user' => ['is_admin' => false]]);

        $this->assertFalse($this->authService->isAdmin($userId, $teamId));

        // Test case: User info not available (e.g., 'user' key missing or 'is_admin' missing)
        $this->authService = Mockery::mock(AuthService::class . '[getUserInfo]');
        $this->authService->shouldReceive('getUserInfo')
            ->once()
            ->with($userId, $teamId)
            ->andReturn(['ok' => true, 'user' => []]); // Missing 'is_admin'

        $this->assertFalse($this->authService->isAdmin($userId, $teamId));

        $this->authService = Mockery::mock(AuthService::class . '[getUserInfo]');
        $this->authService->shouldReceive('getUserInfo')
            ->once()
            ->with($userId, $teamId)
            ->andReturn(['ok' => true]); // Missing 'user' key

        $this->assertFalse($this->authService->isAdmin($userId, $teamId));
    }

    /**
     * Test validateSlackRequest method.
     */
    public function test_validate_slack_request()
    {
        $signingSecret = 'test_signing_secret';
        config(['slack-events-bot.signing_secret' => $signingSecret]);

        // Test case: Valid request
        $timestamp = time();
        $body = 'token=gIkuvaNzCQz2PNYwY2TBCJg2&team_id=T0001&team_domain=example&channel_id=C2147483705&channel_name=fun&user_id=U2147483697&user_name=steve&command=/weather&text=94070&response_url=https://hooks.slack.com/commands/1234/5678&trigger_id=1334522460.139236018';
        $signature = 'v0=' . hash_hmac('sha256', 'v0:' . $timestamp . ':' . $body, $signingSecret);

        $request = Request::create('/slack/events', 'POST', [], [], [], [
            'HTTP_X_SLACK_REQUEST_TIMESTAMP' => $timestamp,
            'HTTP_X_SLACK_SIGNATURE' => $signature,
        ], $body);

        $this->assertTrue($this->authService->validateSlackRequest($request));

        // Test case: Missing timestamp or signature
        Log::shouldReceive('warning')->once()->with('Slack request validation failed: Missing timestamp or signature.');
        $request = Request::create('/slack/events', 'POST', [], [], [], [], $body);
        $this->assertFalse($this->authService->validateSlackRequest($request));

        // Test case: Timestamp expired (replay attack)
        Log::shouldReceive('warning')->once()->with('Slack request validation failed: Timestamp expired (replay attack?).', Mockery::any());
        $timestamp = time() - (60 * 6); // 6 minutes ago
        $signature = 'v0=' . hash_hmac('sha256', 'v0:' . $timestamp . ':' . $body, $signingSecret);
        $request = Request::create('/slack/events', 'POST', [], [], [], [
            'HTTP_X_SLACK_REQUEST_TIMESTAMP' => $timestamp,
            'HTTP_X_SLACK_SIGNATURE' => $signature,
        ], $body);
        $this->assertFalse($this->authService->validateSlackRequest($request));

        // Test case: Signing secret not configured
        Log::shouldReceive('error')->once()->with('Slack request validation failed: Signing secret is not configured.');
        config(['slack-events-bot.signing_secret' => null]);
        $timestamp = time();
        $signature = 'v0=' . hash_hmac('sha256', 'v0:' . $timestamp . ':' . $body, ''); // Empty secret
        $request = Request::create('/slack/events', 'POST', [], [], [], [
            'HTTP_X_SLACK_REQUEST_TIMESTAMP' => $timestamp,
            'HTTP_X_SLACK_SIGNATURE' => $signature,
        ], $body);
        $this->assertFalse($this->authService->validateSlackRequest($request));
        config(['slack-events-bot.signing_secret' => $signingSecret]); // Reset for other tests

        // Test case: Signature mismatch
        Log::shouldReceive('warning')->once()->with('Slack request validation failed: Signature mismatch.');
        $timestamp = time();
        $invalidSignature = 'v0=' . hash_hmac('sha256', 'v0:' . $timestamp . ':' . 'invalid_body', $signingSecret);
        $request = Request::create('/slack/events', 'POST', [], [], [], [
            'HTTP_X_SLACK_REQUEST_TIMESTAMP' => $timestamp,
            'HTTP_X_SLACK_SIGNATURE' => $invalidSignature,
        ], $body);
        $this->assertFalse($this->authService->validateSlackRequest($request));
    }
}
