<?php

namespace HackGreenville\SlackEventsBot\Tests;

use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use HackGreenville\SlackEventsBot\Services\EventService;
use HackGreenville\SlackEventsBot\Services\MessageBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        
        // Add channel
        $channel = $this->databaseService->addChannel($channelId);
        $this->assertDatabaseHas('slack_channels', ['slack_channel_id' => $channelId]);
        
        // Get channels
        $channels = $this->databaseService->getSlackChannelIds();
        $this->assertTrue($channels->contains($channelId));
        
        // Remove channel
        $this->databaseService->removeChannel($channelId);
        $this->assertDatabaseMissing('slack_channels', ['slack_channel_id' => $channelId]);
    }

    public function test_can_create_and_get_messages()
    {
        $channelId = 'C1234567890';
        $this->databaseService->addChannel($channelId);
        
        $week = Carbon::now()->startOfWeek();
        $message = 'Test message content';
        $timestamp = '1234567890.123456';
        
        // Create message
        $this->databaseService->createMessage($week, $message, $timestamp, $channelId, 0);
        
        // Get messages
        $messages = $this->databaseService->getMessages($week);
        $this->assertCount(1, $messages);
        $this->assertEquals($message, $messages->first()['message']);
        $this->assertEquals($channelId, $messages->first()['slack_channel_id']);
    }

    public function test_event_parsing()
    {
        $eventData = [
            'event_name' => 'Test Event',
            'group_name' => 'Test Group',
            'description' => 'This is a test event description',
            'venue' => [
                'name' => 'Test Venue',
                'address' => '123 Main St',
                'city' => 'Greenville',
                'state' => 'SC',
                'zip' => '29601',
                'lat' => null,
                'lon' => null,
            ],
            'time' => '2024-01-15T18:00:00-05:00',
            'url' => 'https://example.com/event',
            'status' => 'upcoming',
            'uuid' => 'test-uuid-123',
        ];

        $event = $this->eventService->createEventFromJson($eventData);
        
        $this->assertEquals('Test Event', $event['title']);
        $this->assertEquals('Test Group', $event['group_name']);
        $this->assertEquals('Test Venue at 123 Main St Greenville, SC 29601', $event['location']);
        $this->assertInstanceOf(Carbon::class, $event['time']);
        
        // Test block generation
        $blocks = $this->eventService->generateBlocks($event);
        $this->assertIsArray($blocks);
        $this->assertEquals('header', $blocks[0]['type']);
        $this->assertEquals('section', $blocks[1]['type']);
        
        // Test text generation
        $text = $this->eventService->generateText($event);
        $this->assertStringContainsString('Test Event', $text);
        $this->assertStringContainsString('Test Group', $text);
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
        $weekEnd = $weekStart->copy()->addDays(7);
        
        // Create multiple events
        $events = [];
        for ($i = 0; $i < 10; $i++) {
            $events[] = [
                'event_name' => "Event $i with a very long title that takes up space",
                'group_name' => "Group $i",
                'description' => str_repeat("This is a long description. ", 10),
                'venue' => ['name' => "Venue $i"],
                'time' => $weekStart->copy()->addDays($i % 7)->toIso8601String(),
                'url' => "https://example.com/event-$i",
                'status' => 'upcoming',
                'uuid' => "uuid-$i",
            ];
        }
        
        $eventBlocks = $this->messageBuilderService->buildEventBlocks($events, $weekStart, $weekEnd);
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
                strlen($message['text'])
            );
        }
    }
}
