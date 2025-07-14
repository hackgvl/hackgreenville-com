<?php

namespace HackGreenville\SlackEventsBot\Tests;

use App\Models\Event;
use App\Models\Org;
use App\Models\State;
use App\Models\Venue;
use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Jobs\CheckEventsApi;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use HackGreenville\SlackEventsBot\Services\AuthService;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use HackGreenville\SlackEventsBot\Services\EventService;
use HackGreenville\SlackEventsBot\Services\MessageBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SlackEventsBotTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected DatabaseService $databaseService;
    protected EventService $eventService;
    protected MessageBuilderService $messageBuilderService;
    protected AuthService $authService; // Added for mocking

    protected function setUp(): void
    {
        parent::setUp();

        // Bind services from the container
        $this->databaseService = app(DatabaseService::class);
        $this->eventService = app(EventService::class);
        $this->messageBuilderService = app(MessageBuilderService::class);

        // Mock AuthService to control isAdmin behavior in tests
        $this->authService = $this->mock(AuthService::class);
        $this->authService->shouldReceive('validateSlackRequest')->andReturn(true); // Allow middleware to pass
        $this->app->instance(AuthService::class, $this->authService);

        // Set up config values that the controller relies on
        config()->set('slack-events-bot.client_id', 'test_client_id');
        config()->set('slack-events-bot.client_secret', 'test_client_secret');
        config()->set('slack-events-bot.scopes', ['commands', 'chat:write']);
        config()->set('slack-events-bot.check_api_cooldown_minutes', 15); // For cooldown tests
        config()->set('slack-events-bot.max_message_character_length', 3000); // For chunking tests
    }

    /**
     * Test the /slack/install route.
     * Verifies that the route returns an HTML response with the "Add to Slack" button
     * and sets a session state.
     */
    public function test_install_route()
    {
        $response = $this->get('/slack/install');

        $response->assertStatus(200);
        $response->assertSee('Add to Slack');
        $response->assertSee('https://slack.com/oauth/v2/authorize?');
        $response->assertSee('client_id=test_client_id');
        $response->assertSee('scope=commands%2Cchat%3Awrite');

        // Assert that a session state was set
        $response->assertSessionHas('slack_oauth_state');
    }

    /**
     * Test the /slack/auth route for a successful OAuth callback.
     * Mocks the Slack API response and verifies workspace creation.
     */
    public function test_auth_route_success()
    {
        $code = 'test_code';
        $state = uniqid('slack_', true);
        Session::put('slack_oauth_state', $state);

        // Mock the HTTP call to Slack's oauth.v2.access endpoint
        Http::fake([
            'https://slack.com/api/oauth.v2.access' => Http::response([
                'ok' => true,
                'access_token' => 'xoxb-test-token',
                'team' => ['id' => 'T123', 'name' => 'Test Team'],
                'authed_user' => ['id' => 'U123'],
                'bot_user_id' => 'BU123', // Corrected bot_user_id location
                'incoming_webhook' => ['channel' => 'C123', 'channel_id' => 'C123', 'configuration_url' => 'http://example.com'],
            ], 200),
            'https://slack.com/api/users.info' => Http::response([
                'ok' => true,
                'user' => ['is_admin' => true],
            ], 200),
        ]);

        $response = $this->get("/slack/auth?code={$code}&state={$state}");

        $response->assertStatus(200);
        $response->assertSee('The HackGreenville API bot has been installed successfully!');

        // Assert that the session state was forgotten
        $response->assertSessionMissing('slack_oauth_state');

        // Assert that the workspace was created in the database
        $workspace = SlackWorkspace::where('team_id', 'T123')->first();
        $this->assertNotNull($workspace);
        $this->assertEquals('T123', $workspace->team_id);
        $this->assertEquals('Test Team', $workspace->team_name);
        $this->assertEquals('xoxb-test-token', $workspace->access_token);
    }

    /**
     * Test the /slack/auth route when the state parameter is invalid.
     */
    public function test_auth_route_invalid_state()
    {
        $code = 'test_code';
        $validState = uniqid('slack_', true);
        $invalidState = 'wrong_state';
        Session::put('slack_oauth_state', $validState);

        $response = $this->get("/slack/auth?code={$code}&state={$invalidState}");

        $response->assertStatus(400);
        $response->assertSee('Invalid state. Your session may have expired or you tried to use an old authorization link.');
        $response->assertSessionHas('slack_oauth_state', $validState); // State should still be there if it didn't match
    }

    /**
     * Test the /slack/auth route when Slack's API returns an error.
     */
    public function test_auth_route_slack_error()
    {
        $code = 'test_code';
        $state = uniqid('slack_', true);
        Session::put('slack_oauth_state', $state);

        // Mock the HTTP call to Slack's oauth.v2.access endpoint to return an error
        Http::fake([
            'https://slack.com/api/oauth.v2.access' => Http::response([
                'ok' => false,
                'error' => 'invalid_code',
            ], 200),
        ]);

        $response = $this->get("/slack/auth?code={$code}&state={$state}");

        $response->assertStatus(400);
        $response->assertSee('The authorization code is invalid or has expired.');
        $response->assertSessionMissing('slack_oauth_state'); // Should still forget the state
    }

    /**
     * Test the /slack/events route for URL verification.
     */
    public function test_events_url_verification()
    {
        $challenge = $this->faker->uuid;
        $payload = [
            'token' => 'test_token',
            'challenge' => $challenge,
            'type' => 'url_verification',
        ];

        $response = $this->postJson('/slack/events', $payload);

        $response->assertStatus(200);
        $response->assertContent($challenge);
    }

    /**
     * Test the /slack/events route for the /add_channel command as an admin.
     */
    public function test_events_add_channel_command_as_admin()
    {
        $workspace = SlackWorkspace::factory()->create(['team_id' => 'T123']);
        $channelId = 'C1234567890';
        $userId = 'U_ADMIN';

        // Mock isAdmin to return true
        $this->authService->shouldReceive('isAdmin')
            ->once()
            ->with($userId, $workspace->team_id)
            ->andReturn(true);

        $payload = [
            'token' => 'test_token',
            'team_id' => $workspace->team_id,
            'channel_id' => $channelId,
            'user_id' => $userId,
            'command' => '/add_channel',
            'text' => '',
            'response_url' => 'http://example.com/response',
            'trigger_id' => 'trigger_id',
        ];

        $response = $this->postJson('/slack/events', $payload);

        $response->assertStatus(200);
        $response->assertSee('Added channel to slack events bot 👍');
        $this->assertDatabaseHas('slack_channels', [
            'slack_channel_id' => $channelId,
            'slack_workspace_id' => $workspace->id,
        ]);
    }

    /**
     * Test the /slack/events route for the /add_channel command when not an admin.
     */
    public function test_events_add_channel_command_not_admin()
    {
        $workspace = SlackWorkspace::factory()->create(['team_id' => 'T123']);
        $channelId = 'C1234567890';
        $userId = 'U_NON_ADMIN';

        // Mock isAdmin to return false
        $this->authService->shouldReceive('isAdmin')
            ->once()
            ->with($userId, $workspace->team_id)
            ->andReturn(false);

        $payload = [
            'token' => 'test_token',
            'team_id' => $workspace->team_id,
            'channel_id' => $channelId,
            'user_id' => $userId,
            'command' => '/add_channel',
            'text' => '',
            'response_url' => 'http://example.com/response',
            'trigger_id' => 'trigger_id',
        ];

        $response = $this->postJson('/slack/events', $payload);

        $response->assertStatus(200);
        $response->assertSee('You must be a workspace admin in order to run `/add_channel`');
        $this->assertDatabaseMissing('slack_channels', [
            'slack_channel_id' => $channelId,
        ]);
    }

    /**
     * Test the /slack/events route for the /remove_channel command as an admin.
     */
    public function test_events_remove_channel_command_as_admin()
    {
        $workspace = SlackWorkspace::factory()->create(['team_id' => 'T123']);
        $channel = $this->databaseService->addChannel('C1234567890', $workspace->team_id);
        $userId = 'U_ADMIN';

        // Mock isAdmin to return true
        $this->authService->shouldReceive('isAdmin')
            ->once()
            ->with($userId, $workspace->team_id)
            ->andReturn(true);

        $payload = [
            'token' => 'test_token',
            'team_id' => $workspace->team_id,
            'channel_id' => $channel->slack_channel_id,
            'user_id' => $userId,
            'command' => '/remove_channel',
            'text' => '',
            'response_url' => 'http://example.com/response',
            'trigger_id' => 'trigger_id',
        ];

        $response = $this->postJson('/slack/events', $payload);

        $response->assertStatus(200);
        $response->assertSee('Removed channel from slack events bot 👍');
        $this->assertDatabaseMissing('slack_channels', [
            'slack_channel_id' => $channel->slack_channel_id,
        ]);
    }

    /**
     * Test the /slack/events route for the /remove_channel command when not an admin.
     */
    public function test_events_remove_channel_command_not_admin()
    {
        $workspace = SlackWorkspace::factory()->create(['team_id' => 'T123']);
        $channel = $this->databaseService->addChannel('C1234567890', $workspace->team_id);
        $userId = 'U_NON_ADMIN';

        // Mock isAdmin to return false
        $this->authService->shouldReceive('isAdmin')
            ->once()
            ->with($userId, $workspace->team_id)
            ->andReturn(false);

        $payload = [
            'token' => 'test_token',
            'team_id' => $workspace->team_id,
            'channel_id' => $channel->slack_channel_id,
            'user_id' => $userId,
            'command' => '/remove_channel',
            'text' => '',
            'response_url' => 'http://example.com/response',
            'trigger_id' => 'trigger_id',
        ];

        $response = $this->postJson('/slack/events', $payload);

        $response->assertStatus(200);
        $response->assertSee('You must be a workspace admin in order to run `/remove_channel`');
        $this->assertDatabaseHas('slack_channels', [ // Should still be in DB
            'slack_channel_id' => $channel->slack_channel_id,
        ]);
    }

    /**
     * Test the /slack/events route for the /check_api command.
     * Verifies that the job is dispatched and a success message is returned.
     */
    public function test_events_check_api_command()
    {
        Queue::fake(); // Prevent actual job dispatch
        SlackWorkspace::factory()->create(['team_id' => 'T123', 'team_name' => 'Test Team', 'access_token' => 'xoxb-test-token', 'domain' => 'testdomain']);

        $userId = 'U123';
        $teamId = 'T123';
        $teamDomain = 'testdomain';

        // Mock isAdmin as true for simplicity, though not strictly required for /check_api
        $this->authService->shouldReceive('isAdmin')
            ->andReturn(true);

        $payload = [
            'token' => 'test_token',
            'team_id' => $teamId,
            'channel_id' => 'C456',
            'user_id' => $userId,
            'command' => '/check_api',
            'team_domain' => $teamDomain,
        ];

        $response = $this->postJson('/slack/events', $payload);

        $response->assertStatus(200);
        $response->assertSee('Checking api for events 👍');

        // Assert that the CheckEventsApi job was dispatched
        Queue::assertPushed(CheckEventsApi::class);

        // Assert cooldown was created
        $this->assertNotNull($this->databaseService->getCooldownExpiryTime($teamDomain, 'check_api'));
    }

    /**
     * Test the /slack/events route for the /check_api command when on cooldown.
     */
    public function test_events_check_api_command_cooldown()
    {
        Queue::fake();
        SlackWorkspace::factory()->create(['team_id' => 'T123', 'team_name' => 'Test Team', 'access_token' => 'xoxb-test-token', 'domain' => 'testdomain']);

        $userId = 'U123';
        $teamId = 'T123';
        $teamDomain = 'testdomain';

        // Create a cooldown that is in the future
        $this->databaseService->createCooldown($teamDomain, 'check_api', 15); // 15 minutes from now

        // Mock isAdmin as true for simplicity
        $this->authService->shouldReceive('isAdmin')
            ->andReturn(true);

        $payload = [
            'token' => 'test_token',
            'team_id' => $teamId,
            'channel_id' => 'C456',
            'user_id' => $userId,
            'command' => '/check_api',
            'team_domain' => $teamDomain,
        ];

        $response = $this->postJson('/slack/events', $payload);

        $response->assertStatus(200);
        $response->assertSee('This command has been run recently and is on a cooldown period.');

        // Assert that the CheckEventsApi job was NOT dispatched
        Queue::assertNotPushed(CheckEventsApi::class);
    }

    /**
     * Test the /slack/events route for an unknown slash command.
     */
    public function test_events_unknown_command()
    {
        $payload = [
            'token' => 'test_token',
            'team_id' => 'T123',
            'channel_id' => 'C456',
            'user_id' => 'U123',
            'command' => '/unknown_command',
            'text' => '',
            'response_url' => 'http://example.com/response',
            'trigger_id' => 'trigger_id',
        ];

        $response = $this->postJson('/slack/events', $payload);

        $response->assertStatus(400);
        $response->assertSee('Unknown command');
    }

    // Original tests (unmodified as they test service logic directly, not HTTP endpoints)

    public function test_can_add_and_remove_channel()
    {
        $workspace = SlackWorkspace::factory()->create();
        $channelId = 'C1234567890';
        $this->databaseService->addChannel($channelId, $workspace->team_id);
        $this->assertDatabaseHas('slack_channels', ['slack_channel_id' => $channelId]);

        $channels = $this->databaseService->getSlackChannels();
        $this->assertTrue($channels->contains(fn ($channel) => $channel->slack_channel_id === $channelId));

        $this->databaseService->removeChannel($channelId);
        $this->assertDatabaseMissing('slack_channels', ['slack_channel_id' => $channelId]);
    }

    public function test_can_create_and_get_messages()
    {
        $workspace = SlackWorkspace::factory()->create();
        $channelId = 'C1234567890';
        $channel = $this->databaseService->addChannel($channelId, $workspace->team_id);
        $this->assertDatabaseHas('slack_channels', ['slack_channel_id' => $channelId]);

        $week = Carbon::now()->startOfWeek();
        $message = 'Test message content';
        $timestamp = '1234567890.123456';
        $sequencePosition = 0;

        $this->databaseService->createMessage($week, $message, $timestamp, $channelId, $sequencePosition);

        $this->assertDatabaseHas('slack_messages', [
            'message' => $message,
            'message_timestamp' => $timestamp,
            'week' => $week->toDateTimeString(),
            'sequence_position' => $sequencePosition,
            'channel_id' => $channel->id,
        ]);

        $messages = $this->databaseService->getMessages($week);
        $this->assertCount(1, $messages);
        $this->assertEquals($message, $messages->first()->message);
        $this->assertEquals($channelId, $messages->first()->channel->slack_channel_id);
    }

    public function test_event_parsing()
    {
        $state = State::factory()->create(['abbr' => 'SC']);

        $organization = Org::factory()->create(['title' => 'Test Group']);
        $venue = Venue::factory()->create([
            'name' => 'Test Venue',
            'address' => '123 Main St',
            'city' => 'Greenville',
            'state_id' => $state->id,
            'zipcode' => '29601',
        ]);
        $event = Event::factory()->create([
            'event_name' => 'Test Event',
            'description' => 'This is a test event description',
            'uri' => 'https://example.com/event',
            'event_uuid' => $this->faker->uuid,
            'active_at' => Carbon::now()->addDays(7),
            'organization_id' => $organization->id,
            'venue_id' => $venue->id,
        ]);

        $event->load('organization', 'venue');

        $blocks = $this->eventService->generateBlocks($event);
        $this->assertIsArray($blocks);
        $this->assertEquals('header', $blocks[0]['type']);
        $this->assertEquals('section', $blocks[1]['type']);
        $this->assertEquals('Test Event', $blocks[0]['text']['text']);
        $this->assertArrayHasKey('type', $blocks[1]['text']);
        $this->assertArrayHasKey('text', $blocks[1]['text']);
        $this->assertStringContainsString('This is a test event description', $blocks[1]['text']['text']);

        $text = $this->eventService->generateText($event);
        $this->assertStringContainsString('Test Event', $text);
        $this->assertStringContainsString('Test Group', $text);
        $this->assertStringContainsString('Test Venue', $text);
        $this->assertStringContainsString('https://example.com/event', $text);
        $this->assertStringContainsString('Upcoming ✅', $text);
    }

    public function test_cooldown_functionality()
    {
        $accessor = 'test-workspace';
        $resource = 'check_api';

        // No cooldown initially
        $expiry = $this->databaseService->getCooldownExpiryTime($accessor, $resource);
        $this->assertNull($expiry);

        // Create cooldown
        $this->databaseService->createCooldown($accessor, $resource, 15);

        // Check cooldown exists and is in future
        $expiry = $this->databaseService->getCooldownExpiryTime($accessor, $resource);
        $this->assertNotNull($expiry);
        $this->assertTrue($expiry->isFuture());
    }

    public function test_message_chunking()
    {
        $weekStart = Carbon::now()->startOfWeek();
        $state = State::factory()->create(['abbr' => 'SC']);

        $eventsData = [];
        for ($i = 0; $i < 10; $i++) {
            $organization = Org::factory()->create(['title' => "Group {$i}"]);
            $venue = Venue::factory()->create([
                'name' => "Venue {$i}",
                'address' => "{$i} Main St",
                'city' => 'Greenville',
                'state_id' => $state->id,
                'zipcode' => '29601',
            ]);

            $event = Event::factory()->create([
                'event_name' => "Event {$i} with a very long title that takes up space",
                'description' => str_repeat("This is a long description. ", 10),
                'uri' => "https://example.com/event-{$i}",
                'active_at' => $weekStart->copy()->addDays($i % 7),
                'organization_id' => $organization->id,
                'venue_id' => $venue->id,
                'event_uuid' => $this->faker->uuid,
            ]);

            $event->load('organization', 'venue');

            $eventsData[] = $event;
        }

        $eventsCollection = collect($eventsData);

        $eventBlocks = $this->messageBuilderService->buildEventBlocks($eventsCollection);
        $this->assertInstanceOf(Collection::class, $eventBlocks);
        $this->assertGreaterThan(0, $eventBlocks->count());

        $chunkedMessages = $this->messageBuilderService->chunkMessages($eventBlocks, $weekStart);
        $this->assertIsArray($chunkedMessages);
        $this->assertGreaterThan(0, count($chunkedMessages));

        // Verify each message has required structure
        foreach ($chunkedMessages as $message) {
            $this->assertArrayHasKey('blocks', $message);
            $this->assertArrayHasKey('text', $message);
            $this->assertLessThan(
                config('slack-events-bot.max_message_character_length'),
                mb_strlen($message['text'])
            );
        }
    }
}
