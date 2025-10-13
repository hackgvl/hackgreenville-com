<?php

namespace HackGreenville\SlackEventsBot\Tests\Services;

use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackCooldown;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class DatabaseServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DatabaseService $databaseService;

    protected function setUp(): void
    {
        parent::setUp();

        // Workaround for RefreshDatabase not consistently cleaning up SlackChannel table
        SlackChannel::truncate();

        $this->databaseService = new DatabaseService;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_creates_a_message(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $week = Carbon::now();
        $message = 'Test message';
        $messageTimestamp = '12345.6789';
        $slackChannelId = $channel->slack_channel_id;
        $sequencePosition = 1;

        $slackMessage = $this->databaseService->createMessage(
            $week,
            $message,
            $messageTimestamp,
            $slackChannelId,
            $sequencePosition
        );

        $this->assertInstanceOf(SlackMessage::class, $slackMessage);
        $this->assertDatabaseHas('slack_messages', [
            'week' => $week->toDateTimeString(),
            'message' => $message,
            'message_timestamp' => $messageTimestamp,
            'channel_id' => $channel->id,
            'sequence_position' => $sequencePosition,
        ]);
    }

    /** @test */
    public function it_updates_a_message(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $week = Carbon::now();
        $oldMessage = 'Old message';
        $newMessage = 'New message';
        $messageTimestamp = '12345.6789';
        $slackChannelId = $channel->slack_channel_id;
        $sequencePosition = 1;

        SlackMessage::create([
            'week' => $week->toDateTimeString(),
            'message' => $oldMessage,
            'message_timestamp' => $messageTimestamp,
            'channel_id' => $channel->id,
            'sequence_position' => $sequencePosition,
        ]);

        $updatedRows = $this->databaseService->updateMessage(
            $week,
            $newMessage,
            $messageTimestamp,
            $slackChannelId
        );

        $this->assertEquals(1, $updatedRows);
        $this->assertDatabaseHas('slack_messages', [
            'week' => $week->toDateTimeString(),
            'message' => $newMessage,
            'message_timestamp' => $messageTimestamp,
            'channel_id' => $channel->id,
        ]);
        $this->assertDatabaseMissing('slack_messages', [
            'message' => $oldMessage,
        ]);
    }

    /** @test */
    public function it_gets_messages_for_a_week(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel1 = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);
        $channel2 = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $week = Carbon::now();

        SlackMessage::create([
            'week' => $week->toDateTimeString(),
            'message' => 'Message 1',
            'message_timestamp' => '1',
            'channel_id' => $channel1->id,
            'sequence_position' => 1,
        ]);
        SlackMessage::create([
            'week' => $week->toDateTimeString(),
            'message' => 'Message 2',
            'message_timestamp' => '2',
            'channel_id' => $channel2->id,
            'sequence_position' => 2,
        ]);
        SlackMessage::create([
            'week' => Carbon::now()->subWeek()->toDateTimeString(), // Different week
            'message' => 'Message 3',
            'message_timestamp' => '3',
            'channel_id' => $channel1->id,
            'sequence_position' => 3,
        ]);

        $messages = $this->databaseService->getMessages($week);

        $this->assertCount(2, $messages);
        $this->assertEquals('Message 1', $messages->first()->message);
        $this->assertEquals('Message 2', $messages->last()->message);
    }

    /** @test */
    public function it_gets_most_recent_message_for_channel(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        SlackMessage::create([
            'week' => Carbon::now()->subDays(2)->toDateTimeString(),
            'message' => 'Old message',
            'message_timestamp' => '1',
            'channel_id' => $channel->id,
            'sequence_position' => 1,
        ]);
        $expectedMessage = SlackMessage::create([
            'week' => Carbon::now()->toDateTimeString(),
            'message' => 'New message',
            'message_timestamp' => '2',
            'channel_id' => $channel->id,
            'sequence_position' => 2,
        ]);

        $mostRecentMessage = $this->databaseService->getMostRecentMessageForChannel($channel->slack_channel_id);

        $this->assertIsArray($mostRecentMessage);
        $this->assertEquals($expectedMessage->message, $mostRecentMessage['message']);
        $this->assertEquals($expectedMessage->message_timestamp, $mostRecentMessage['message_timestamp']);
    }

    /** @test */
    public function it_returns_null_if_channel_not_found_for_most_recent_message(): void
    {
        $mostRecentMessage = $this->databaseService->getMostRecentMessageForChannel('non-existent-channel');
        $this->assertNull($mostRecentMessage);
    }

    /** @test */
    public function it_returns_null_if_no_messages_for_channel(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $mostRecentMessage = $this->databaseService->getMostRecentMessageForChannel($channel->slack_channel_id);
        $this->assertNull($mostRecentMessage);
    }

    /** @test */
    public function it_gets_slack_channels(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        SlackChannel::factory()->count(3)->create(['slack_workspace_id' => $workspace->id]);

        $channels = $this->databaseService->getSlackChannels();

        $this->assertCount(3, $channels);
        $this->assertInstanceOf(SlackChannel::class, $channels->first());
    }

    /** @test */
    public function it_adds_a_channel(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $slackChannelId = 'C12345';
        $teamId = $workspace->team_id;

        $channel = $this->databaseService->addChannel($slackChannelId, $teamId);

        $this->assertInstanceOf(SlackChannel::class, $channel);
        $this->assertDatabaseHas('slack_channels', [
            'slack_channel_id' => $slackChannelId,
            'slack_workspace_id' => $workspace->id,
        ]);
    }

    /** @test */
    public function it_removes_a_channel(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $deletedRows = $this->databaseService->removeChannel($channel->slack_channel_id);

        $this->assertEquals(1, $deletedRows);
        $this->assertDatabaseMissing('slack_channels', [
            'id' => $channel->id,
        ]);
    }

    /** @test */
    public function it_deletes_messages_for_a_week(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $weekToDelete = Carbon::now();
        $otherWeek = Carbon::now()->subWeek();

        SlackMessage::create([
            'week' => $weekToDelete->toDateTimeString(),
            'message' => 'Message 1',
            'message_timestamp' => '1',
            'channel_id' => $channel->id,
            'sequence_position' => 1,
        ]);
        SlackMessage::create([
            'week' => $weekToDelete->toDateTimeString(),
            'message' => 'Message 2',
            'message_timestamp' => '2',
            'channel_id' => $channel->id,
            'sequence_position' => 2,
        ]);
        SlackMessage::create([
            'week' => Carbon::now()->subWeek()->toDateTimeString(), // Different week
            'message' => 'Message 3',
            'message_timestamp' => '3',
            'channel_id' => $channel->id,
            'sequence_position' => 1,
        ]);

        $deletedRows = $this->databaseService->deleteMessagesForWeek($weekToDelete);

        $this->assertEquals(2, $deletedRows);
        $this->assertDatabaseMissing('slack_messages', [
            'message' => 'Message 1',
        ]);
        $this->assertDatabaseMissing('slack_messages', [
            'message' => 'Message 2',
        ]);
        $this->assertDatabaseHas('slack_messages', [
            'message' => 'Message 3',
        ]);
    }

    /** @test */
    public function it_deletes_a_specific_message(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $messageToDelete = SlackMessage::create([
            'week' => Carbon::now()->toDateTimeString(),
            'message' => 'Message to delete',
            'message_timestamp' => '123',
            'channel_id' => $channel->id,
            'sequence_position' => 1,
        ]);
        SlackMessage::create([
            'week' => Carbon::now()->toDateTimeString(),
            'message' => 'Another message',
            'message_timestamp' => '456',
            'channel_id' => $channel->id,
            'sequence_position' => 2,
        ]);

        $deletedRows = $this->databaseService->deleteMessage($channel->slack_channel_id, $messageToDelete->message_timestamp);

        $this->assertEquals(1, $deletedRows);
        $this->assertDatabaseMissing('slack_messages', [
            'id' => $messageToDelete->id,
        ]);
        $this->assertDatabaseHas('slack_messages', [
            'message_timestamp' => '456',
        ]);
    }

    /** @test */
    public function it_handles_deleting_message_in_non_existent_channel(): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->with('Attempted to delete message in non-existent channel: non-existent-channel');

        $deletedRows = $this->databaseService->deleteMessage('non-existent-channel', '123');

        $this->assertEquals(0, $deletedRows);
    }

    /** @test */
    public function it_deletes_old_messages_and_cooldowns(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        // Create messages
        SlackMessage::create([
            'week' => Carbon::now()->subDays(100)->toDateTimeString(),
            'message' => 'Old message 1',
            'message_timestamp' => '1',
            'channel_id' => $channel->id,
            'sequence_position' => 1,
        ]);
        SlackMessage::create([
            'week' => Carbon::now()->subDays(50)->toDateTimeString(),
            'message' => 'Recent message 1',
            'message_timestamp' => '2',
            'channel_id' => $channel->id,
            'sequence_position' => 2,
        ]);

        // Create cooldowns
        SlackCooldown::create([
            'accessor' => 'user1',
            'resource' => 'resource1',
            'expires_at' => Carbon::now()->subDays(100),
        ]);
        SlackCooldown::create([
            'accessor' => 'user2',
            'resource' => 'resource2',
            'expires_at' => Carbon::now()->addDays(10),
        ]);

        $this->databaseService->deleteOldMessages(90);

        $this->assertDatabaseMissing('slack_messages', [
            'message' => 'Old message 1',
        ]);
        $this->assertDatabaseHas('slack_messages', [
            'message' => 'Recent message 1',
        ]);
        $this->assertDatabaseMissing('slack_cooldowns', [
            'accessor' => 'user1',
        ]);
        $this->assertDatabaseHas('slack_cooldowns', [
            'accessor' => 'user2',
        ]);
    }

    /** @test */
    public function it_creates_or_updates_a_cooldown(): void
    {
        $accessor = 'user1';
        $resource = 'resource1';
        $cooldownMinutes = 5;

        // Create new cooldown
        $cooldown = $this->databaseService->createCooldown($accessor, $resource, $cooldownMinutes);

        $this->assertInstanceOf(SlackCooldown::class, $cooldown);
        $this->assertDatabaseHas('slack_cooldowns', [
            'accessor' => $accessor,
            'resource' => $resource,
        ]);

        // Update existing cooldown
        $newCooldownMinutes = 10;
        $updatedCooldown = $this->databaseService->createCooldown($accessor, $resource, $newCooldownMinutes);

        $this->assertInstanceOf(SlackCooldown::class, $updatedCooldown);
        $this->assertEquals($cooldown->id, $updatedCooldown->id);
        $this->assertDatabaseHas('slack_cooldowns', [
            'accessor' => $accessor,
            'resource' => $resource,
            'expires_at' => $updatedCooldown->expires_at,
        ]);
    }

    /** @test */
    public function it_gets_cooldown_expiry_time(): void
    {
        $accessor = 'user1';
        $resource = 'resource1';
        $expiresAt = Carbon::now()->addMinutes(10);

        SlackCooldown::create([
            'accessor' => $accessor,
            'resource' => $resource,
            'expires_at' => $expiresAt,
        ]);

        $expiryTime = $this->databaseService->getCooldownExpiryTime($accessor, $resource);

        $this->assertInstanceOf(Carbon::class, $expiryTime);
        $this->assertEquals($expiresAt->toDateTimeString(), $expiryTime->toDateTimeString());
    }

    /** @test */
    public function it_returns_null_for_non_existent_cooldown_expiry_time(): void
    {
        $expiryTime = $this->databaseService->getCooldownExpiryTime('non-existent', 'resource');
        $this->assertNull($expiryTime);
    }

    /** @test */
    public function it_creates_or_updates_a_workspace(): void
    {
        $data = [
            'team' => [
                'id' => 'T123',
                'name' => 'Test Team',
            ],
            'access_token' => 'xoxb-test-token',
            'bot_user_id' => 'B123',
        ];

        // Create new workspace
        $workspace = $this->databaseService->createOrUpdateWorkspace($data);

        $this->assertInstanceOf(SlackWorkspace::class, $workspace);
        $this->assertDatabaseHas('slack_workspaces', [
            'team_id' => 'T123',
            'team_name' => 'Test Team',
            'bot_user_id' => 'B123',
        ]);
        // Retrieve the created workspace to check the decrypted token
        $createdWorkspace = SlackWorkspace::where('team_id', 'T123')->first();
        $this->assertEquals('xoxb-test-token', $createdWorkspace->access_token);


        // Update existing workspace
        $updatedData = [
            'team' => [
                'id' => 'T123',
                'name' => 'Updated Team',
            ],
            'access_token' => 'xoxb-updated-token',
            'bot_user_id' => 'B456',
        ];

        $updatedWorkspace = $this->databaseService->createOrUpdateWorkspace($updatedData);

        $this->assertInstanceOf(SlackWorkspace::class, $updatedWorkspace);
        $this->assertEquals($workspace->id, $updatedWorkspace->id);
        $this->assertDatabaseHas('slack_workspaces', [
            'team_id' => 'T123',
            'team_name' => 'Updated Team',
            'bot_user_id' => 'B456',
        ]);
        // Retrieve the updated workspace to check the decrypted token
        $retrievedUpdatedWorkspace = SlackWorkspace::where('team_id', 'T123')->first();
        $this->assertEquals('xoxb-updated-token', $retrievedUpdatedWorkspace->access_token);
    }
}
