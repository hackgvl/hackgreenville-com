<?php

namespace HackGreenville\SlackEventsBot\Tests\Services;

use App\Models\Event;
use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Exceptions\UnsafeMessageSpilloverException;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use HackGreenville\SlackEventsBot\Services\BotService;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use HackGreenville\SlackEventsBot\Services\MessageBuilderService;
use HackGreenville\SlackEventsBot\Services\SlackApiClient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mockery;
use Tests\DatabaseTestCase;

class BotServiceTest extends DatabaseTestCase
{
    private $databaseServiceMock;
    private $messageBuilderServiceMock;
    private $botService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->databaseServiceMock = Mockery::mock(DatabaseService::class);
        $this->messageBuilderServiceMock = Mockery::mock(MessageBuilderService::class);

        $this->botService = new BotService(
            $this->databaseServiceMock,
            $this->messageBuilderServiceMock,
            new SlackApiClient
        );
    }

    public function test_handle_posting_to_slack_no_events()
    {
        Log::spy();

        $this->botService = Mockery::mock(BotService::class . '[getEventsForWeek, deleteMessagesForWeek]', [
            $this->databaseServiceMock,
            $this->messageBuilderServiceMock,
            new SlackApiClient,
        ])->makePartial()->shouldAllowMockingProtectedMethods();

        $this->botService->shouldReceive('getEventsForWeek')
            ->atLeast()->once()
            ->andReturn(new Collection);

        $this->botService->shouldReceive('deleteMessagesForWeek')
            ->atLeast()->once();

        $this->botService->handlePostingToSlack();

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'No upcoming events found for the week of'));
    }

    public function test_handle_posting_to_slack_with_events()
    {
        Log::spy();

        $events = new Collection([Mockery::mock(Event::class)]);

        $this->botService = Mockery::mock(BotService::class . '[getEventsForWeek]', [
            $this->databaseServiceMock,
            $this->messageBuilderServiceMock,
            new SlackApiClient,
        ])->makePartial()->shouldAllowMockingProtectedMethods();

        $this->botService->shouldReceive('getEventsForWeek')
            ->atLeast()->once()
            ->andReturn($events);

        $this->messageBuilderServiceMock->shouldReceive('buildEventBlocks')
            ->atLeast()->once()
            ->with(Mockery::any())
            ->andReturn(new Collection);

        $this->messageBuilderServiceMock->shouldReceive('chunkMessages')
            ->atLeast()->once()
            ->with(Mockery::any(), Mockery::any())
            ->andReturn([]);

        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->atLeast()->once()
            ->andReturn(new Collection);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->atLeast()->once()
            ->andReturn(new Collection);

        $this->botService->handlePostingToSlack();

        $this->assertTrue(true);
    }

    public function test_parse_events_for_week()
    {
        Log::spy();

        $events = new Collection([Mockery::mock(Event::class)]);
        $weekStart = Carbon::now()->startOfWeek();
        $eventBlocks = new Collection([['type' => 'section', 'text' => ['type' => 'mrkdwn', 'text' => 'Event 1']]]);
        $chunkedMessages = [['text' => 'Message 1', 'blocks' => []]];

        $this->messageBuilderServiceMock->shouldReceive('buildEventBlocks')
            ->once()
            ->with(Mockery::any())
            ->andReturn($eventBlocks);

        $this->messageBuilderServiceMock->shouldReceive('chunkMessages')
            ->once()
            ->with(Mockery::any(), Mockery::any())
            ->andReturn($chunkedMessages);

        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn(new Collection);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->andReturn(new Collection);

        $this->botService->parseEventsForWeek($events, $weekStart);

        $this->assertTrue(true);
    }

    public function test_post_or_update_messages_post_new()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'New message', 'blocks' => []]];
        $channels = new Collection([
            (object) ['slack_channel_id' => 'C123', 'workspace' => (object) ['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection;

        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn($channels);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->with($week)
            ->andReturn($existingMessages);

        $this->databaseServiceMock->shouldReceive('getMostRecentMessageForChannel')
            ->zeroOrMoreTimes()
            ->andReturn(null);

        Http::fake([
            'https://slack.com/api/chat.postMessage' => Http::response(['ok' => true, 'ts' => '123.456'], 200),
        ]);

        $this->databaseServiceMock->shouldReceive('createMessage')
            ->once()
            ->with($week, 'New message', '123.456', 'C123', 0);

        $this->botService->postOrUpdateMessages($week, $messages);

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'Posting new message'));
    }

    public function test_post_or_update_messages_update_existing()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'Updated message', 'blocks' => []]];
        $channels = new Collection([
            (object) ['slack_channel_id' => 'C123', 'workspace' => (object) ['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection([
            (object) [
                'channel' => (object) ['slack_channel_id' => 'C123'],
                'sequence_position' => 0,
                'message_timestamp' => '123.456',
                'message' => 'Old message',
            ],
        ]);

        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn($channels);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->with($week)
            ->andReturn($existingMessages);

        $this->databaseServiceMock->shouldReceive('getMostRecentMessageForChannel')
            ->zeroOrMoreTimes()
            ->andReturn(null);

        Http::fake([
            'https://slack.com/api/chat.update' => Http::response(['ok' => true], 200),
        ]);

        $this->databaseServiceMock->shouldReceive('updateMessage')
            ->once()
            ->with($week, 'Updated message', '123.456', 'C123');

        $this->botService->postOrUpdateMessages($week, $messages);

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'Updating message'));
    }

    public function test_post_or_update_messages_delete_old()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = []; // No new messages
        $channels = new Collection([
            (object) ['slack_channel_id' => 'C123', 'workspace' => (object) ['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection([
            (object) [
                'channel' => (object) ['slack_channel_id' => 'C123'],
                'sequence_position' => 0,
                'message_timestamp' => '123.456',
                'message' => 'Old message to be deleted',
            ],
        ]);

        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn($channels);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->with($week)
            ->andReturn($existingMessages);

        Http::fake([
            'https://slack.com/api/chat.delete' => Http::response(['ok' => true], 200),
        ]);

        $this->databaseServiceMock->shouldReceive('deleteMessage')
            ->once()
            ->with('C123', '123.456');

        $this->botService->postOrUpdateMessages($week, $messages);

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'Deleting old message'));
    }

    public function test_post_or_update_messages_unsafe_spillover_exception()
    {
        Log::spy();

        Http::fake([
            'https://slack.com/api/chat.update' => Http::response(['ok' => true], 200),
            'https://slack.com/api/chat.postMessage' => Http::response(['ok' => true, 'ts' => '123.456'], 200),
        ]);

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'New message 1', 'blocks' => []], ['text' => 'New message 2', 'blocks' => []]];
        $channels = new Collection([
            (object) ['slack_channel_id' => 'C123', 'workspace' => (object) ['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection([
            (object) [
                'channel' => (object) ['slack_channel_id' => 'C123'],
                'sequence_position' => 0,
                'message_timestamp' => '123.456',
                'message' => 'Old message',
            ],
        ]);

        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn($channels);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->with($week)
            ->andReturn($existingMessages);

        $this->databaseServiceMock->shouldReceive('getMostRecentMessageForChannel')
            ->once()
            ->andReturn(new SlackMessage(['week' => $week->copy()->addWeek()->toDateTimeString()]));

        $this->databaseServiceMock->shouldNotReceive('updateMessage');

        $this->expectException(UnsafeMessageSpilloverException::class);

        $this->botService->postOrUpdateMessages($week, $messages);
    }

    public function test_post_or_update_messages_slack_api_error_update_logs_and_continues()
    {
        Log::spy();

        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'Updated message', 'blocks' => []]];
        $channels = new Collection([
            (object) ['slack_channel_id' => 'C123', 'workspace' => (object) ['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection([
            (object) [
                'channel' => (object) ['slack_channel_id' => 'C123'],
                'sequence_position' => 0,
                'message_timestamp' => '123.456',
                'message' => 'Old message',
            ],
        ]);

        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn($channels);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->with($week)
            ->andReturn($existingMessages);

        $this->databaseServiceMock->shouldReceive('getMostRecentMessageForChannel')
            ->zeroOrMoreTimes()
            ->andReturn(null);

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
        $channels = new Collection([
            (object) ['slack_channel_id' => 'C_FAIL', 'workspace' => (object) ['access_token' => 'xoxb-token']],
            (object) ['slack_channel_id' => 'C_OK', 'workspace' => (object) ['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection;

        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn($channels);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->with($week)
            ->andReturn($existingMessages);

        $this->databaseServiceMock->shouldReceive('getMostRecentMessageForChannel')
            ->zeroOrMoreTimes()
            ->andReturn(null);

        Http::fake([
            'https://slack.com/api/chat.postMessage' => Http::sequence()
                ->push(['ok' => false, 'error' => 'channel_not_found'], 200)
                ->push(['ok' => true, 'ts' => '789.012'], 200),
        ]);

        $this->databaseServiceMock->shouldReceive('createMessage')
            ->once()
            ->with($week, 'New message', '789.012', 'C_OK', 0);

        $this->botService->postOrUpdateMessages($week, $messages);

        Log::shouldHaveReceived('error')
            ->withArgs(fn ($message) => Str::contains($message, 'Failed to post/update messages for channel C_FAIL'));

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => Str::contains($message, 'Posting new message'));
    }

}
