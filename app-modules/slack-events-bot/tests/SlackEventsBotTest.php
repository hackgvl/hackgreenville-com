<?php

namespace HackGreenville\SlackEventsBot\Tests;

use App\Models\Event;
use App\Models\Org;
use App\Models\State;
use App\Models\Venue;
use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use HackGreenville\SlackEventsBot\Services\EventService;
use HackGreenville\SlackEventsBot\Services\MessageBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SlackEventsBotTest extends TestCase
{
    use RefreshDatabase;

    protected DatabaseService $databaseService;
    protected EventService $eventService;
    protected MessageBuilderService $messageBuilderService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->databaseService = app(DatabaseService::class);
        $this->eventService = app(EventService::class);
        $this->messageBuilderService = app(MessageBuilderService::class);
    }

    public function test_can_add_and_remove_channel()
    {
        $channelId = 'C1234567890';
        $this->databaseService->addChannel($channelId);
        $this->assertDatabaseHas('slack_channels', ['slack_channel_id' => $channelId]);

        $channels = $this->databaseService->getSlackChannelIds();
        $this->assertTrue($channels->contains($channelId));

        $this->databaseService->removeChannel($channelId);
        $this->assertDatabaseMissing('slack_channels', ['slack_channel_id' => $channelId]);
    }

    public function test_can_create_and_get_messages()
    {
        $channelId = 'C1234567890';
        $channel = $this->databaseService->addChannel($channelId);
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
        $this->assertEquals($message, $messages->first()['message']);
        $this->assertEquals($channelId, $messages->first()['slack_channel_id']);
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
            'event_uuid' => 'test-uuid-123',
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
                'event_uuid' => 'test-uuid-123-' . $i,
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
