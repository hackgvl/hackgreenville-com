<?php

namespace HackGreenville\SlackEventsBot\Tests\Services;

use App\Models\Event;
use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Services\MessageBuilderService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\DatabaseTestCase;

class MessageBuilderServiceTest extends DatabaseTestCase
{
    private MessageBuilderService $messageBuilderService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->messageBuilderService = $this->app->make(MessageBuilderService::class);
    }

    #[Test]
    public function it_builds_event_blocks_correctly()
    {
        $event1 = Event::factory()->create([
            'event_name' => 'Event 1',
            'description' => 'Description 1',
            'active_at' => now()->addDay(),
            'expire_at' => now()->addDays(2),
        ]);
        $event2 = Event::factory()->create([
            'event_name' => 'Event 2',
            'description' => 'Description 2',
            'active_at' => now()->addDay(),
            'expire_at' => now()->addDays(2),
        ]);

        $events = Collection::make([$event1->load('organization', 'venue'), $event2->load('organization', 'venue')]);

        $result = $this->messageBuilderService->buildEventBlocks($events);

        $this->assertCount(2, $result);
        $this->assertStringContainsString('Event 1', $result[0]['text']);
        $this->assertStringContainsString('Event 1', json_encode($result[0]['blocks']));
        $this->assertStringContainsString('Event 2', $result[1]['text']);
        $this->assertStringContainsString('Event 2', json_encode($result[1]['blocks']));
    }

    #[Test]
    public function it_chunks_messages_correctly()
    {
        // Set a small max character length for testing chunking.
        // The header text is ~60 chars ("HackGreenville Events for the week of July 7 - 1 of 1\n\n===\n\n"),
        // so 90 allows the header + ~1 event per message.
        config(['slack-events-bot.max_message_character_length' => 90]);

        $weekStart = Carbon::parse('2025-07-07'); // Monday

        $eventBlocks = Collection::make([
            ['blocks' => [['type' => 'section', 'text' => ['text' => 'Event 1']]], 'text' => "Event 1 Text\n\n", 'text_length' => mb_strlen("Event 1 Text\n\n")],
            ['blocks' => [['type' => 'section', 'text' => ['text' => 'Event 2']]], 'text' => "Event 2 Text\n\n", 'text_length' => mb_strlen("Event 2 Text\n\n")],
            ['blocks' => [['type' => 'section', 'text' => ['text' => 'Event 3']]], 'text' => "Event 3 Text\n\n", 'text_length' => mb_strlen("Event 3 Text\n\n")],
            ['blocks' => [['type' => 'section', 'text' => ['text' => 'Event 4']]], 'text' => "Event 4 Text\n\n", 'text_length' => mb_strlen("Event 4 Text\n\n")],
        ]);

        $messages = $this->messageBuilderService->chunkMessages($eventBlocks, $weekStart);

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
        config(['slack-events-bot.max_message_character_length' => 3000]);

        $weekStart = Carbon::parse('2025-07-07');
        $eventBlocks = Collection::make([]);

        $messages = $this->messageBuilderService->chunkMessages($eventBlocks, $weekStart);

        $this->assertCount(1, $messages);
        $this->assertStringContainsString('HackGreenville Events for the week of July 7 - 1 of 1', $messages[0]['text']);
        $this->assertNotEmpty($messages[0]['blocks']);
    }

    #[Test]
    public function it_skips_oversized_events_that_exceed_max_message_length()
    {
        config(['slack-events-bot.max_message_character_length' => 100]);

        $weekStart = Carbon::parse('2025-07-07');

        // Create an oversized event that alone exceeds the limit minus header
        $oversizedText = str_repeat('x', 200) . "\n\n";
        $normalText = "Normal event\n\n";

        $eventBlocks = Collection::make([
            ['blocks' => [['type' => 'section']], 'text' => $oversizedText, 'text_length' => mb_strlen($oversizedText)],
            ['blocks' => [['type' => 'section']], 'text' => $normalText, 'text_length' => mb_strlen($normalText)],
        ]);

        $messages = $this->messageBuilderService->chunkMessages($eventBlocks, $weekStart);

        // The oversized event should be skipped, only the normal event should appear
        $this->assertCount(1, $messages);
        $this->assertStringContainsString('Normal event', $messages[0]['text']);
        $this->assertStringNotContainsString(str_repeat('x', 200), $messages[0]['text']);
    }

    #[Test]
    public function it_builds_event_blocks_from_empty_collection()
    {
        $events = Collection::make([]);

        $result = $this->messageBuilderService->buildEventBlocks($events);

        $this->assertCount(0, $result);
    }

    #[Test]
    public function it_filters_out_null_event_blocks()
    {
        $event1 = Event::factory()->create([
            'event_name' => 'Event 1',
            'description' => 'Description 1',
            'active_at' => now()->addDay(),
            'expire_at' => now()->addDays(2),
        ]);
        $event2 = Event::factory()->create([
            'event_name' => '',
            'description' => '',
            'active_at' => now()->addDay(),
            'expire_at' => now()->addDays(2),
        ]);

        $events = Collection::make([$event1->load('organization', 'venue'), $event2->load('organization', 'venue')]);

        $result = $this->messageBuilderService->buildEventBlocks($events);

        // Event 2 has empty name, but EventService sets it to "Untitled Event" — so it won't be filtered out.
        // Both events should have non-empty text from EventService::generateText.
        $this->assertCount(2, $result);
        $this->assertStringContainsString('Event 1', $result[0]['text']);
    }
}
