<?php

namespace HackGreenville\SlackEventsBot\Tests\Services;

use App\Models\Event;
use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Services\EventService;
use HackGreenville\SlackEventsBot\Services\MessageBuilderService;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MessageBuilderServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_builds_event_blocks_correctly()
    {
        $eventServiceMock = Mockery::mock(EventService::class);
        $messageBuilderService = new MessageBuilderService($eventServiceMock);

        $event1 = new Event(['name' => 'Event 1', 'description' => 'Description 1']);
        $event2 = new Event(['name' => 'Event 2', 'description' => 'Description 2']);
        $events = Collection::make([$event1, $event2]);

        $eventServiceMock->shouldReceive('generateText')
            ->with($event1)
            ->andReturn('Event 1 Text');
        $eventServiceMock->shouldReceive('generateBlocks')
            ->with($event1)
            ->andReturn([['type' => 'section', 'text' => ['type' => 'mrkdwn', 'text' => 'Event 1 Block']]]);

        $eventServiceMock->shouldReceive('generateText')
            ->with($event2)
            ->andReturn('Event 2 Text');
        $eventServiceMock->shouldReceive('generateBlocks')
            ->with($event2)
            ->andReturn([['type' => 'section', 'text' => ['type' => 'mrkdwn', 'text' => 'Event 2 Block']]]);

        $result = $messageBuilderService->buildEventBlocks($events);

        $this->assertCount(2, $result);
        $this->assertEquals('Event 1 Text' . "\n\n", $result[0]['text']);
        $this->assertStringContainsString('Event 1 Block', json_encode($result[0]['blocks']));
        $this->assertEquals('Event 2 Text' . "\n\n", $result[1]['text']);
        $this->assertStringContainsString('Event 2 Block', json_encode($result[1]['blocks']));
    }

    #[Test]
    public function it_chunks_messages_correctly()
    {
        $eventServiceMock = Mockery::mock(EventService::class);
        $messageBuilderService = new MessageBuilderService($eventServiceMock);

        // Set a small max character length for testing chunking.
        // The header text is ~60 chars ("HackGreenville Events for the week of July 7 - 1 of 1\n\n===\n\n"),
        // so 90 allows the header + ~1 event per message.
        config(['slack-events-bot.max_message_character_length' => 90]);

        $weekStart = Carbon::parse('2025-07-07'); // Monday

        $eventBlocks = Collection::make([
            ['blocks' => [['type' => 'section', 'text' => ['text' => 'Event 1']]], 'text' => 'Event 1 Text' . "\n\n", 'text_length' => mb_strlen('Event 1 Text' . "\n\n")],
            ['blocks' => [['type' => 'section', 'text' => ['text' => 'Event 2']]], 'text' => 'Event 2 Text' . "\n\n", 'text_length' => mb_strlen('Event 2 Text' . "\n\n")],
            ['blocks' => [['type' => 'section', 'text' => ['text' => 'Event 3']]], 'text' => 'Event 3 Text' . "\n\n", 'text_length' => mb_strlen('Event 3 Text' . "\n\n")],
            ['blocks' => [['type' => 'section', 'text' => ['text' => 'Event 4']]], 'text' => 'Event 4 Text' . "\n\n", 'text_length' => mb_strlen('Event 4 Text' . "\n\n")],
        ]);

        $messages = $messageBuilderService->chunkMessages($eventBlocks, $weekStart);

        // Based on the small max_message_character_length, we expect multiple messages.
        // The exact number depends on the header length and event text lengths.
        // Let's assume each event text is around 15 chars, and header is around 50 chars.
        // Max content length per message = 100 - 50 = 50 chars.
        // Each event text is 15 chars. So, 3 events per message (3 * 15 = 45).
        // With 4 events, we should get 2 messages.
        $this->assertGreaterThanOrEqual(2, count($messages));

        // Verify the first message
        $this->assertStringContainsString('HackGreenville Events for the week of July 7 - 1 of', $messages[0]['text']);
        $this->assertStringContainsString('Event 1 Text', $messages[0]['text']);
        $this->assertStringContainsString('Event 2 Text', $messages[0]['text']);

        // Verify the second message
        $this->assertStringContainsString('HackGreenville Events for the week of July 7 - 2 of', $messages[1]['text']);
        $this->assertStringContainsString('Event 3 Text', $messages[1]['text']);
        $this->assertStringContainsString('Event 4 Text', $messages[1]['text']);
    }

    #[Test]
    public function it_chunks_empty_event_blocks_into_single_message_with_header()
    {
        $eventServiceMock = Mockery::mock(EventService::class);
        $messageBuilderService = new MessageBuilderService($eventServiceMock);

        config(['slack-events-bot.max_message_character_length' => 3000]);

        $weekStart = Carbon::parse('2025-07-07');
        $eventBlocks = Collection::make([]);

        $messages = $messageBuilderService->chunkMessages($eventBlocks, $weekStart);

        $this->assertCount(1, $messages);
        $this->assertStringContainsString('HackGreenville Events for the week of July 7 - 1 of 1', $messages[0]['text']);
        $this->assertNotEmpty($messages[0]['blocks']);
    }

    #[Test]
    public function it_skips_oversized_events_that_exceed_max_message_length()
    {
        $eventServiceMock = Mockery::mock(EventService::class);
        $messageBuilderService = new MessageBuilderService($eventServiceMock);

        config(['slack-events-bot.max_message_character_length' => 100]);

        $weekStart = Carbon::parse('2025-07-07');

        // Create an oversized event that alone exceeds the limit minus header
        $oversizedText = str_repeat('x', 200) . "\n\n";
        $normalText = 'Normal event' . "\n\n";

        $eventBlocks = Collection::make([
            ['blocks' => [['type' => 'section']], 'text' => $oversizedText, 'text_length' => mb_strlen($oversizedText)],
            ['blocks' => [['type' => 'section']], 'text' => $normalText, 'text_length' => mb_strlen($normalText)],
        ]);

        $messages = $messageBuilderService->chunkMessages($eventBlocks, $weekStart);

        // The oversized event should be skipped, only the normal event should appear
        $this->assertCount(1, $messages);
        $this->assertStringContainsString('Normal event', $messages[0]['text']);
        $this->assertStringNotContainsString(str_repeat('x', 200), $messages[0]['text']);
    }

    #[Test]
    public function it_builds_event_blocks_from_empty_collection()
    {
        $eventServiceMock = Mockery::mock(EventService::class);
        $messageBuilderService = new MessageBuilderService($eventServiceMock);

        $events = Collection::make([]);

        $result = $messageBuilderService->buildEventBlocks($events);

        $this->assertCount(0, $result);
    }

    #[Test]
    public function it_filters_out_null_event_blocks()
    {
        $eventServiceMock = Mockery::mock(EventService::class);
        $messageBuilderService = new MessageBuilderService($eventServiceMock);

        $event1 = new Event(['name' => 'Event 1']);
        $event2 = new Event(['name' => 'Event 2']);
        $events = Collection::make([$event1, $event2]);

        $eventServiceMock->shouldReceive('generateText')
            ->with($event1)
            ->andReturn('Event 1 Text');
        $eventServiceMock->shouldReceive('generateBlocks')
            ->with($event1)
            ->andReturn([['type' => 'section', 'text' => ['type' => 'mrkdwn', 'text' => 'Event 1 Block']]]);

        // Simulate an event that should be skipped (e.g., generateBlocks returns null or empty)
        $eventServiceMock->shouldReceive('generateText')
            ->with($event2)
            ->andReturn(''); // Empty text will cause buildSingleEventBlock to return null
        $eventServiceMock->shouldReceive('generateBlocks')
            ->with($event2)
            ->andReturn([]); // Empty blocks will cause buildSingleEventBlock to return null

        $result = $messageBuilderService->buildEventBlocks($events);

        $this->assertCount(1, $result);
        $this->assertEquals('Event 1 Text' . "\n\n", $result[0]['text']);
    }
}
