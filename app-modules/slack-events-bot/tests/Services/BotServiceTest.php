<?php

namespace HackGreenville\SlackEventsBot\Tests\Services;

use App\Models\Event;
use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use HackGreenville\SlackEventsBot\Services\BotService;
use Illuminate\Http\Client\Request;
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

    public function test_post_or_update_messages_spillover_adds_calendar_link_instead_of_posting()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'New message 1', 'blocks' => []], ['text' => 'New message 2', 'blocks' => []]];
        $calendarUrl = route('calendar.index');
        $linkText = 'Click here to view more events for this week';

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

        $this->botService->postOrUpdateMessages($week, $messages);

        Http::assertSent(function (Request $request) use ($calendarUrl, $linkText) {
            if ($request->url() !== 'https://slack.com/api/chat.update') {
                return false;
            }

            $blocks = $request['blocks'];
            $lastBlock = $blocks[array_key_last($blocks)];

            return str_contains($request['text'], 'New message 1')
                && str_contains($request['text'], $linkText)
                && str_contains($request['text'], $calendarUrl)
                && ! str_contains($request['text'], 'New message 2')
                && ($lastBlock['type'] ?? null) === 'section'
                && ($lastBlock['text']['text'] ?? null) === '<' . $calendarUrl . '|' . $linkText . '>';
        });

        Http::assertNotSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.postMessage');
        Http::assertNotSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.delete');

        $this->assertDatabaseHas('slack_messages', [
            'message_timestamp' => '123.456',
        ]);

        $updated = SlackMessage::where('message_timestamp', '123.456')->first();
        $this->assertStringContainsString('New message 1', $updated->message);
        $this->assertStringContainsString($linkText, $updated->message);
        $this->assertStringContainsString($calendarUrl, $updated->message);

        $this->assertDatabaseHas('slack_messages', [
            'message_timestamp' => '789.012',
            'message' => 'Next week message',
        ]);
        $this->assertDatabaseCount('slack_messages', 2);

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'Linking the last message to the calendar'));
    }

    public function test_post_or_update_messages_spillover_link_is_only_on_the_last_existing_message()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [
            ['text' => 'Updated A', 'blocks' => []],
            ['text' => 'Updated B', 'blocks' => []],
            ['text' => 'Updated C', 'blocks' => []],
        ];
        $linkText = 'Click here to view more events for this week';

        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create([
            'slack_channel_id' => 'C123',
            'slack_workspace_id' => $workspace->id,
        ]);

        SlackMessage::factory()->create([
            'week' => $week->toDateTimeString(),
            'message' => 'Old A',
            'message_timestamp' => '111.111',
            'channel_id' => $channel->id,
            'sequence_position' => 0,
        ]);
        SlackMessage::factory()->create([
            'week' => $week->toDateTimeString(),
            'message' => 'Old B',
            'message_timestamp' => '222.222',
            'channel_id' => $channel->id,
            'sequence_position' => 1,
        ]);
        SlackMessage::factory()->create([
            'week' => $week->copy()->addWeek()->toDateTimeString(),
            'message' => 'Next week message',
            'message_timestamp' => '789.012',
            'channel_id' => $channel->id,
            'sequence_position' => 0,
        ]);

        Http::fake([
            'https://slack.com/api/chat.update' => Http::response(['ok' => true], 200),
            'https://slack.com/api/chat.postMessage' => Http::response(['ok' => true, 'ts' => '333.333'], 200),
        ]);

        $this->botService->postOrUpdateMessages($week, $messages);

        Http::assertSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.update'
            && $request['text'] === 'Updated A'
            && $request['ts'] === '111.111');

        Http::assertSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.update'
                && $request['ts'] === '222.222'
                && str_contains($request['text'], 'Updated B')
                && str_contains($request['text'], $linkText)
                && ! str_contains($request['text'], 'Updated C'));

        Http::assertNotSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.postMessage');

        $this->assertDatabaseHas('slack_messages', [
            'message_timestamp' => '111.111',
            'message' => 'Updated A',
        ]);
        $this->assertDatabaseMissing('slack_messages', [
            'message_timestamp' => '333.333',
        ]);
    }

    public function test_post_or_update_messages_spillover_link_is_not_sent_again_when_unchanged()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'New message 1', 'blocks' => []], ['text' => 'New message 2', 'blocks' => []]];

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
        SlackMessage::factory()->create([
            'week' => $week->copy()->addWeek()->toDateTimeString(),
            'message' => 'Next week message',
            'message_timestamp' => '789.012',
            'channel_id' => $channel->id,
            'sequence_position' => 0,
        ]);

        Http::fake([
            'https://slack.com/api/chat.update' => Http::response(['ok' => true], 200),
        ]);

        $this->botService->postOrUpdateMessages($week, $messages);
        $this->botService->postOrUpdateMessages($week, $messages);

        Http::assertSentCount(1);

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, "hasn't changed, not updating"));
    }

    public function test_post_or_update_messages_posts_additional_messages_when_no_newer_week_exists()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'Updated message', 'blocks' => []], ['text' => 'Second message', 'blocks' => []]];
        $linkText = 'Click here to view more events for this week';

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
            'https://slack.com/api/chat.postMessage' => Http::response(['ok' => true, 'ts' => '999.111'], 200),
        ]);

        $this->botService->postOrUpdateMessages($week, $messages);

        Http::assertSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.update'
            && $request['text'] === 'Updated message'
            && ! str_contains($request['text'], $linkText));

        Http::assertSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.postMessage'
            && $request['text'] === 'Second message'
            && ! str_contains($request['text'], $linkText));

        $this->assertDatabaseHas('slack_messages', [
            'message' => 'Second message',
            'message_timestamp' => '999.111',
            'sequence_position' => 1,
        ]);
    }

    public function test_spillover_fallback_does_not_change_messages_posted_to_other_channels()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'Part 1', 'blocks' => []], ['text' => 'Part 2', 'blocks' => []]];
        $linkText = 'Click here to view more events for this week';

        $workspace = SlackWorkspace::factory()->create();
        $spillingChannel = SlackChannel::factory()->create([
            'slack_channel_id' => 'C_SPILL',
            'slack_workspace_id' => $workspace->id,
        ]);
        SlackChannel::factory()->create([
            'slack_channel_id' => 'C_OPEN',
            'slack_workspace_id' => $workspace->id,
        ]);

        SlackMessage::factory()->create([
            'week' => $week->toDateTimeString(),
            'message' => 'Old part',
            'message_timestamp' => '123.456',
            'channel_id' => $spillingChannel->id,
            'sequence_position' => 0,
        ]);
        SlackMessage::factory()->create([
            'week' => $week->copy()->addWeek()->toDateTimeString(),
            'message' => 'Next week message',
            'message_timestamp' => '789.012',
            'channel_id' => $spillingChannel->id,
            'sequence_position' => 0,
        ]);

        Http::fake([
            'https://slack.com/api/chat.update' => Http::response(['ok' => true], 200),
            'https://slack.com/api/chat.postMessage' => Http::sequence()
                ->push(['ok' => true, 'ts' => '555.001'], 200)
                ->push(['ok' => true, 'ts' => '555.002'], 200),
        ]);

        $this->botService->postOrUpdateMessages($week, $messages);

        Http::assertSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.update'
            && $request['channel'] === 'C_SPILL'
            && str_contains($request['text'], 'Part 1')
            && str_contains($request['text'], $linkText)
            && ! str_contains($request['text'], 'Part 2'));

        Http::assertSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.postMessage'
            && $request['channel'] === 'C_OPEN'
            && $request['text'] === 'Part 1');

        Http::assertSent(fn (Request $request) => $request->url() === 'https://slack.com/api/chat.postMessage'
            && $request['channel'] === 'C_OPEN'
            && $request['text'] === 'Part 2');
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
