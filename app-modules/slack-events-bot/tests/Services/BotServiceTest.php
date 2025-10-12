<?php

namespace HackGreenville\SlackEventsBot\Tests\Services;

use App\Models\Event;
use Carbon\Carbon;
use Exception;
use HackGreenville\SlackEventsBot\Exceptions\UnsafeMessageSpilloverException;
use HackGreenville\SlackEventsBot\Services\BotService;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use HackGreenville\SlackEventsBot\Services\MessageBuilderService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class BotServiceTest extends TestCase
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
            $this->messageBuilderServiceMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test handlePostingToSlack when no events are found.
     */
    public function test_handle_posting_to_slack_no_events()
    {
        $this->refreshApplication();

        $this->botService = Mockery::mock(BotService::class . '[getEventsForWeek, deleteMessagesForWeek]', [
            $this->databaseServiceMock,
            $this->messageBuilderServiceMock
        ])->makePartial()->shouldAllowMockingProtectedMethods();

        // Expect getEventsForWeek to be called and return an empty collection
        $this->botService->shouldReceive('getEventsForWeek')
            ->once()
            ->andReturn(new Collection);

        // Expect deleteMessagesForWeek to be called
        $this->botService->shouldReceive('deleteMessagesForWeek')
            ->once();

        // Expect Log::info for no events found
        Log::shouldReceive('info')
            ->once()
            ->withArgs(fn ($message) => str_contains($message, 'No upcoming events found for the week of'));

        // Call the real handlePostingToSlack method on the botService instance
        $this->botService->handlePostingToSlack();

        $this->assertTrue(true); // Assert that the test ran without issues
    }

    /**
     * Test handlePostingToSlack when events are found.
     */
    public function test_handle_posting_to_slack_with_events()
    {
        $events = new Collection([Mockery::mock(Event::class)]);

        $eventMock = Mockery::mock(Event::class);
        $queryBuilderMock = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);

        $eventMock->shouldReceive('newQuery')->andReturn($queryBuilderMock);
        $queryBuilderMock->shouldReceive('with')->andReturnSelf();
        $queryBuilderMock->shouldReceive('published')->andReturnSelf();
        $queryBuilderMock->shouldReceive('whereBetween')->andReturnSelf();
        $queryBuilderMock->shouldReceive('oldest')->andReturnSelf();
        $queryBuilderMock->shouldReceive('get')->andReturn($events);

        $this->app->instance(Event::class, $eventMock);

        // Expect messageBuilderService methods to be called
        $this->messageBuilderServiceMock->shouldReceive('buildEventBlocks')
            ->once()
            ->with(Mockery::any()) // Use Mockery::any() for argument matching
            ->andReturn(new Collection); // Return a Collection

        $this->messageBuilderServiceMock->shouldReceive('chunkMessages')
            ->once()
            ->with(Mockery::any(), Mockery::any())
            ->andReturn([]);

        // Expect postOrUpdateMessages to be called
        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn(new Collection);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->andReturn(new Collection);

        // Expect Log::info for posting/updating messages (from postOrUpdateMessages)
        Log::shouldReceive('info')
            ->zeroOrMoreTimes();

        $this->botService->handlePostingToSlack();

        $this->assertTrue(true); // Assert that the test ran without issues
    }

    /**
     * Test parseEventsForWeek method.
     */
    public function test_parse_events_for_week()
    {
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

        // Mock dependencies for postOrUpdateMessages
        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn(new Collection);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->andReturn(new Collection);

        Log::shouldReceive('info')
            ->zeroOrMoreTimes();

        $this->botService->parseEventsForWeek($events, $weekStart);

        $this->assertTrue(true); // Assert that the test ran without issues
    }

    /**
     * Test postOrUpdateMessages - posting a new message.
     */
    public function test_post_or_update_messages_post_new()
    {
        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'New message', 'blocks' => []]];
        $channels = new Collection([
            (object)['slack_channel_id' => 'C123', 'workspace' => (object)['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection;

        $this->databaseServiceMock->shouldReceive('getSlackChannels')
            ->once()
            ->andReturn($channels);

        $this->databaseServiceMock->shouldReceive('getMessages')
            ->once()
            ->with($week)
            ->andReturn($existingMessages);

        // Mock isUnsafeToSpillover indirectly by mocking getMostRecentMessageForChannel
        $this->databaseServiceMock->shouldReceive('getMostRecentMessageForChannel')
            ->zeroOrMoreTimes()
            ->andReturn(null); // Simulate safe spillover

        Log::shouldReceive('info')
            ->once()
            ->withArgs(fn ($message) => str_contains($message, 'Posting new message'));

        Http::shouldReceive('withToken')
            ->once()
            ->with('xoxb-token')
            ->andReturnSelf();

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse->shouldReceive('successful')->andReturn(true);
        $mockResponse->shouldReceive('json')->andReturn(['ok' => true, 'ts' => '123.456']);

        Http::shouldReceive('post')
            ->once()
            ->with('https://slack.com/api/chat.postMessage', Mockery::any())
            ->andReturn($mockResponse);

        $this->databaseServiceMock->shouldReceive('createMessage')
            ->once()
            ->with($week, 'New message', '123.456', 'C123', 0);

        $this->botService->postOrUpdateMessages($week, $messages);

        $this->assertTrue(true); // Assert that the test ran without issues
    }

    /**
     * Test postOrUpdateMessages - updating an existing message.
     */
    public function test_post_or_update_messages_update_existing()
    {
        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'Updated message', 'blocks' => []]];
        $channels = new Collection([
            (object)['slack_channel_id' => 'C123', 'workspace' => (object)['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection([
            (object)[
                'channel' => (object)['slack_channel_id' => 'C123'],
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

        // Mock isUnsafeToSpillover indirectly
        $this->databaseServiceMock->shouldReceive('getMostRecentMessageForChannel')
            ->zeroOrMoreTimes()
            ->andReturn(null);

        Log::shouldReceive('info')
            ->once()
            ->withArgs(fn ($message) => str_contains($message, 'Updating message'));

        Http::shouldReceive('withToken')
            ->once()
            ->with('xoxb-token')
            ->andReturnSelf();

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse->shouldReceive('successful')->andReturn(true);
        $mockResponse->shouldReceive('json')->andReturn(['ok' => true]);

        Http::shouldReceive('post')
            ->once()
            ->with('https://slack.com/api/chat.update', Mockery::any())
            ->andReturn($mockResponse);

        $this->databaseServiceMock->shouldReceive('updateMessage')
            ->once()
            ->with($week, 'Updated message', '123.456', 'C123');

        $this->botService->postOrUpdateMessages($week, $messages);

        $this->assertTrue(true); // Assert that the test ran without issues
    }

    /**
     * Test postOrUpdateMessages - deleting an old message.
     */
    public function test_post_or_update_messages_delete_old()
    {
        $week = Carbon::now()->startOfWeek();
        $messages = []; // No new messages
        $channels = new Collection([
            (object)['slack_channel_id' => 'C123', 'workspace' => (object)['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection([
            (object)[
                'channel' => (object)['slack_channel_id' => 'C123'],
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

        Log::shouldReceive('info')
            ->once()
            ->withArgs(fn ($message) => str_contains($message, 'Deleting old message'));

        Http::shouldReceive('withToken')
            ->once()
            ->with('xoxb-token')
            ->andReturnSelf();

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse->shouldReceive('successful')->andReturn(true);
        $mockResponse->shouldReceive('json')->andReturn(['ok' => true]); // Mock json() even if not used directly

        Http::shouldReceive('post')
            ->once()
            ->with('https://slack.com/api/chat.delete', Mockery::any())
            ->andReturn($mockResponse);

        $this->databaseServiceMock->shouldReceive('deleteMessage')
            ->once()
            ->with('C123', '123.456');

        $this->botService->postOrUpdateMessages($week, $messages);

        $this->assertTrue(true); // Assert that the test ran without issues
    }

    /**
     * Test postOrUpdateMessages - UnsafeMessageSpilloverException is thrown.
     */
    public function test_post_or_update_messages_unsafe_spillover_exception()
    {
        // Http::fake() to prevent real HTTP calls
        Http::fake([
            'https://slack.com/api/chat.update' => Http::response(['ok' => true], 200),
            'https://slack.com/api/chat.postMessage' => Http::response(['ok' => true, 'ts' => '123.456'], 200),
        ]);

        $week = Carbon::now()->startOfWeek();
        // Change messages to have more items than existingMessages
        $messages = [['text' => 'New message 1', 'blocks' => []], ['text' => 'New message 2', 'blocks' => []]];
        $channels = new Collection([
            (object)['slack_channel_id' => 'C123', 'workspace' => (object)['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection([
            (object)[
                'channel' => (object)['slack_channel_id' => 'C123'],
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

        // Mock isUnsafeToSpillover to return true
        $this->databaseServiceMock->shouldReceive('getMostRecentMessageForChannel')
            ->once()
            ->andReturn(['week' => $week->copy()->addWeek()->toDateString()]);

        // Ensure updateMessage is NOT called
        $this->databaseServiceMock->shouldNotReceive('updateMessage');

        Log::shouldReceive('info')
            ->zeroOrMoreTimes(); // Add this expectation

        Log::shouldReceive('error')
            ->once()
            ->withArgs(fn ($message) => str_contains($message, 'Cannot update messages for') &&
                       str_contains($message, 'New events have caused the number of messages needed to increase'));

        $this->expectException(UnsafeMessageSpilloverException::class);

        $this->botService->postOrUpdateMessages($week, $messages);
    }

    /**
     * Test postOrUpdateMessages - Slack API error when updating.
     */
    public function test_post_or_update_messages_slack_api_error_update()
    {
        $week = Carbon::now()->startOfWeek();
        $messages = [['text' => 'Updated message', 'blocks' => []]];
        $channels = new Collection([
            (object)['slack_channel_id' => 'C123', 'workspace' => (object)['access_token' => 'xoxb-token']],
        ]);
        $existingMessages = new Collection([
            (object)[
                'channel' => (object)['slack_channel_id' => 'C123'],
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

        // Mock isUnsafeToSpillover indirectly
        $this->databaseServiceMock->shouldReceive('getMostRecentMessageForChannel')
            ->zeroOrMoreTimes()
            ->andReturn(null);

        Log::shouldReceive('info')
            ->once()
            ->withArgs(fn ($message) => str_contains($message, 'Updating message'));

        Http::shouldReceive('withToken')
            ->once()
            ->with('xoxb-token')
            ->andReturnSelf();

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse->shouldReceive('successful')->andReturn(false);
        $mockResponse->shouldReceive('json')->andReturn(['ok' => false, 'error' => 'update_failed']);

        Http::shouldReceive('post')
            ->once()
            ->with('https://slack.com/api/chat.update', Mockery::any())
            ->andReturn($mockResponse);

        Log::shouldReceive('error')
            ->once()
            ->withArgs(fn ($message, $context) => str_contains($message, 'Failed to update message') &&
                       $context['error'] === 'update_failed');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Slack API error when updating message: update_failed');

        $this->botService->postOrUpdateMessages($week, $messages);
    }
}
