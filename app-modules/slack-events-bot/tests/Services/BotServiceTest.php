<?php

namespace HackGreenville\SlackEventsBot\Tests\Services;

use App\Models\Event;
use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Exceptions\UnsafeMessageSpilloverException;
use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use HackGreenville\SlackEventsBot\Services\BotService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\DatabaseTestCase;

class BotServiceTest extends DatabaseTestCase
{
    private BotService $botService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->botService = $this->app->make(BotService::class);
    }

    public function test_handle_posting_to_slack_no_events()
    {
        Log::spy();
        Http::fake();

        $this->botService->handlePostingToSlack();

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'No upcoming events found for the week of'));
    }

    public function test_handle_posting_to_slack_with_events()
    {
        Log::spy();
        Http::fake([
            'https://slack.com/api/chat.postMessage' => Http::response(['ok' => true, 'ts' => '123.456'], 200),
        ]);

        $weekStart = now()->copy()->startOfWeek(Carbon::SUNDAY);

        Event::factory()->create([
            'active_at' => $weekStart->copy()->addDay(),
            'expire_at' => $weekStart->copy()->addDays(2),
        ]);

        $workspace = SlackWorkspace::factory()->create();
        SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $this->botService->handlePostingToSlack();

        $this->assertDatabaseCount('slack_messages', 1);
    }

    public function test_parse_events_for_week()
    {
        Log::spy();
        Http::fake([
            'https://slack.com/api/chat.postMessage' => Http::response(['ok' => true, 'ts' => '123.456'], 200),
        ]);

        $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);

        $events = Event::factory()->count(2)->create([
            'active_at' => $weekStart->copy()->addDay(),
            'expire_at' => $weekStart->copy()->addDays(2),
        ]);

        $workspace = SlackWorkspace::factory()->create();
        SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $this->botService->parseEventsForWeek($events, $weekStart);

        $this->assertDatabaseCount('slack_messages', 1);
    }

    public function test_post_or_update_messages_post_new()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'New message', 'blocks' => []]];

        $workspace = SlackWorkspace::factory()->create();
        SlackChannel::factory()->create([
            'slack_channel_id' => 'C123',
            'slack_workspace_id' => $workspace->id,
        ]);

        Http::fake([
            'https://slack.com/api/chat.postMessage' => Http::response(['ok' => true, 'ts' => '123.456'], 200),
        ]);

        $this->botService->postOrUpdateMessages($week, $messages);

        $this->assertDatabaseHas('slack_messages', [
            'message' => 'New message',
            'message_timestamp' => '123.456',
            'sequence_position' => 0,
        ]);

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'Posting new message'));
    }

    public function test_post_or_update_messages_update_existing()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'Updated message', 'blocks' => []]];

        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create([
            'slack_channel_id' => 'C123',
            'slack_workspace_id' => $workspace->id,
        ]);

        SlackMessage::factory()->create([
            'week' => $week->toDateTimeString(),
            'message' => 'Old message',
            'message_timestamp' => '123.456',
            'channel_id' => $channel->id,
            'sequence_position' => 0,
        ]);

        Http::fake([
            'https://slack.com/api/chat.update' => Http::response(['ok' => true], 200),
        ]);

        $this->botService->postOrUpdateMessages($week, $messages);

        $this->assertDatabaseHas('slack_messages', [
            'message' => 'Updated message',
            'message_timestamp' => '123.456',
        ]);

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'Updating message'));
    }

    public function test_post_or_update_messages_delete_old()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = []; // No new messages

        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create([
            'slack_channel_id' => 'C123',
            'slack_workspace_id' => $workspace->id,
        ]);

        SlackMessage::factory()->create([
            'week' => $week->toDateTimeString(),
            'message' => 'Old message to be deleted',
            'message_timestamp' => '123.456',
            'channel_id' => $channel->id,
            'sequence_position' => 0,
        ]);

        Http::fake([
            'https://slack.com/api/chat.delete' => Http::response(['ok' => true], 200),
        ]);

        $this->botService->postOrUpdateMessages($week, $messages);

        $this->assertDatabaseMissing('slack_messages', [
            'message_timestamp' => '123.456',
        ]);

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'Deleting old message'));
    }

    public function test_post_or_update_messages_unsafe_spillover_exception()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'New message 1', 'blocks' => []], ['text' => 'New message 2', 'blocks' => []]];

        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create([
            'slack_channel_id' => 'C123',
            'slack_workspace_id' => $workspace->id,
        ]);

        // Existing message for current week (1 message)
        SlackMessage::factory()->create([
            'week' => $week->toDateTimeString(),
            'message' => 'Old message',
            'message_timestamp' => '123.456',
            'channel_id' => $channel->id,
            'sequence_position' => 0,
        ]);

        // A message for the next week already exists — makes spillover unsafe
        SlackMessage::factory()->create([
            'week' => $week->copy()->addWeek()->toDateTimeString(),
            'message' => 'Next week message',
            'message_timestamp' => '789.012',
            'channel_id' => $channel->id,
            'sequence_position' => 0,
        ]);

        Http::fake([
            'https://slack.com/api/chat.update' => Http::response(['ok' => true], 200),
            'https://slack.com/api/chat.postMessage' => Http::response(['ok' => true, 'ts' => '123.456'], 200),
        ]);

        $this->expectException(UnsafeMessageSpilloverException::class);

        $this->botService->postOrUpdateMessages($week, $messages);
    }

    public function test_post_or_update_messages_slack_api_error_update_logs_and_continues()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'Updated message', 'blocks' => []]];

        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create([
            'slack_channel_id' => 'C123',
            'slack_workspace_id' => $workspace->id,
        ]);

        SlackMessage::factory()->create([
            'week' => $week->toDateTimeString(),
            'message' => 'Old message',
            'message_timestamp' => '123.456',
            'channel_id' => $channel->id,
            'sequence_position' => 0,
        ]);

        Http::fake([
            'https://slack.com/api/chat.update' => Http::response(['ok' => false, 'error' => 'update_failed'], 200),
        ]);

        $this->botService->postOrUpdateMessages($week, $messages);

        Log::shouldHaveReceived('error')
            ->withArgs(fn ($message) => Str::contains($message, 'Failed to post/update messages for channel C123'));
    }

    public function test_post_or_update_messages_continues_to_next_channel_on_failure()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'New message', 'blocks' => []]];

        $workspace = SlackWorkspace::factory()->create();
        SlackChannel::factory()->create([
            'slack_channel_id' => 'C_FAIL',
            'slack_workspace_id' => $workspace->id,
        ]);
        SlackChannel::factory()->create([
            'slack_channel_id' => 'C_OK',
            'slack_workspace_id' => $workspace->id,
        ]);

        Http::fake([
            'https://slack.com/api/chat.postMessage' => Http::sequence()
                ->push(['ok' => false, 'error' => 'channel_not_found'], 200)
                ->push(['ok' => true, 'ts' => '789.012'], 200),
        ]);

        $this->botService->postOrUpdateMessages($week, $messages);

        Log::shouldHaveReceived('error')
            ->withArgs(fn ($message) => Str::contains($message, 'Failed to post/update messages for channel C_FAIL'));

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'Posting new message'));

        $this->assertDatabaseHas('slack_messages', [
            'message' => 'New message',
            'message_timestamp' => '789.012',
        ]);
    }
}
