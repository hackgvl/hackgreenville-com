<?php

namespace HackGreenGreenville\SlackEventsBot\Tests\Services;

use App\Models\Event;
use App\Models\Org;
use App\Models\Venue;
use HackGreenville\SlackEventsBot\Services\EventService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EventService $eventService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventService = new EventService;
    }

    /** @test */
    public function it_generates_blocks_for_an_event(): void
    {
        // Create related models for the Event
        $organization = Org::factory()->create(['title' => 'Test Organization']);
        $state = \App\Models\State::factory()->create(['name' => 'South Carolina', 'abbr' => 'SC']); // Create a State model
        $venue = Venue::factory()->create([
            'name' => 'Test Venue',
            'address' => '123 Main St',
            'city' => 'Greenville',
            'state_id' => $state->id, // Use state_id
            'zipcode' => '29601' // Use zipcode instead of zip
        ]);

        $event = Event::factory()->create([
            'event_name' => 'My Awesome Event',
            'description' => 'This is a very interesting description for my awesome event.',
            'organization_id' => $organization->id,
            'venue_id' => $venue->id,
            'uri' => 'https://example.com/event',
            'active_at' => now()->addDays(7),
        ]);

        $blocks = $this->eventService->generateBlocks($event);

        $this->assertIsArray($blocks);
        $this->assertCount(2, $blocks); // Header and Section

        // Assert Header Block
        $this->assertEquals('header', $blocks[0]['type']);
        $this->assertEquals('plain_text', $blocks[0]['text']['type']);
        $this->assertEquals('My Awesome Event', $blocks[0]['text']['text']);

        // Assert Section Block
        $this->assertEquals('section', $blocks[1]['type']);
        $this->assertEquals('plain_text', $blocks[1]['text']['type']);
        $this->assertEquals('This is a very interesting description for my awesome event.', $blocks[1]['text']['text']);
        $this->assertIsArray($blocks[1]['fields']);
        $this->assertCount(8, $blocks[1]['fields']); // 4 pairs of mrkdwn/plain_text

        // Assert fields content
        $this->assertEquals('*Test Organization*', $blocks[1]['fields'][0]['text']); // Organization
        $this->assertEquals('<https://example.com/event|*Link* :link:>', $blocks[1]['fields'][1]['text']); // Link
        $this->assertEquals('*Status*', $blocks[1]['fields'][2]['text']); // Status Label
        $this->assertEquals('Upcoming ✅', $blocks[1]['fields'][3]['text']); // Status Value
        $this->assertEquals('*Location*', $blocks[1]['fields'][4]['text']); // Location Label
        $this->assertEquals('Test Venue - 123 Main St Greenville, SC 29601', $blocks[1]['fields'][5]['text']); // Location Value
        $this->assertEquals('*Time*', $blocks[1]['fields'][6]['text']); // Time Label
        $this->assertEquals($event->active_at->format('F j, Y g:i A T'), $blocks[1]['fields'][7]['text']); // Time Value
    }

    /** @test */
    public function it_generates_blocks_with_limited_text(): void
    {
        $longEventName = str_repeat('a', 200);
        $longDescription = str_repeat('b', 300);

        $organization = Org::factory()->create(['title' => 'Test Organization']);
        $state = \App\Models\State::factory()->create(['name' => 'South Carolina', 'abbr' => 'SC']);
        $venue = Venue::factory()->create([
            'name' => 'Test Venue',
            'address' => '123 Main St',
            'city' => 'Greenville',
            'state_id' => $state->id,
            'zipcode' => '29601',
        ]);

        $event = Event::factory()->create([
            'event_name' => $longEventName,
            'description' => $longDescription,
            'organization_id' => $organization->id,
            'venue_id' => $venue->id,
            'uri' => 'https://example.com/event',
            'active_at' => now()->addDays(7),
        ]);

        $blocks = $this->eventService->generateBlocks($event);

        $this->assertEquals(153, mb_strlen($blocks[0]['text']['text']));
        $this->assertStringEndsWith('...', $blocks[0]['text']['text']);
        $this->assertEquals(253, mb_strlen($blocks[1]['text']['text']));
        $this->assertStringEndsWith('...', $blocks[1]['text']['text']);
    }

    /** @test */
    public function it_generates_blocks_with_no_venue(): void
    {
        $organization = Org::factory()->create(['title' => 'Test Organization']);

        $event = Event::factory()->create([
            'event_name' => 'Event Without Venue',
            'description' => 'Description for event without venue.',
            'organization_id' => $organization->id,
            'venue_id' => null,
            'uri' => 'https://example.com/event',
            'active_at' => now()->addDays(7),
        ]);

        $blocks = $this->eventService->generateBlocks($event);

        $this->assertEquals('No location', $blocks[1]['fields'][5]['text']);
    }

    /** @test */
    public function it_generates_blocks_with_empty_event_name(): void
    {
        $organization = Org::factory()->create(['title' => 'Test Organization']);

        $event = Event::factory()->create([
            'event_name' => '',
            'description' => 'Description for event with empty name.',
            'organization_id' => $organization->id,
            'uri' => 'https://example.com/event',
            'active_at' => now()->addDays(7),
        ]);

        $blocks = $this->eventService->generateBlocks($event);

        $this->assertEquals('Untitled Event', $blocks[0]['text']['text']);
    }

    /** @test */
    public function it_generates_text_for_an_event(): void
    {
        $organization = Org::factory()->create(['title' => 'Test Organization']);
        $state = \App\Models\State::factory()->create(['name' => 'South Carolina', 'abbr' => 'SC']);
        $venue = Venue::factory()->create([
            'name' => 'Test Venue',
            'address' => '123 Main St',
            'city' => 'Greenville',
            'state_id' => $state->id,
            'zipcode' => '29601',
        ]);

        $event = Event::factory()->create([
            'event_name' => 'My Awesome Event',
            'description' => 'This is a very interesting description for my awesome event.',
            'organization_id' => $organization->id,
            'venue_id' => $venue->id,
            'uri' => 'https://example.com/event',
            'active_at' => now()->addDays(7),
        ]);

        $expectedText = sprintf(
            "%s\nOrganization: %s\nDescription: %s\nLink: %s\nStatus: %s\nLocation: %s\nTime: %s",
            'My Awesome Event',
            'Test Organization',
            'This is a very interesting description for my awesome event.',
            'https://example.com/event',
            'Upcoming ✅',
            'Test Venue',
            $event->active_at->format('F j, Y g:i A T')
        );

        $generatedText = $this->eventService->generateText($event);

        $this->assertEquals($expectedText, $generatedText);
    }

    /** @test */
    public function it_generates_text_with_limited_text(): void
    {
        $longEventName = str_repeat('x', 300);
        $longOrgTitle = str_repeat('y', 255);
        $longDescription = str_repeat('z', 300);

        $organization = Org::factory()->create(['title' => $longOrgTitle]);

        $event = Event::factory()->create([
            'event_name' => $longEventName,
            'description' => $longDescription,
            'organization_id' => $organization->id,
            'venue_id' => null,
            'uri' => 'https://example.com/event',
            'active_at' => now()->addDays(7),
        ]);

        $generatedText = $this->eventService->generateText($event);

        $expectedEventName = mb_substr($longEventName, 0, 250) . '...';
        $expectedOrgTitle = mb_substr($longOrgTitle, 0, 250) . '...';
        $expectedDescription = mb_substr($longDescription, 0, 250) . '...';

        $expectedText = sprintf(
            "%s\nOrganization: %s\nDescription: %s\nLink: %s\nStatus: %s\nLocation: %s\nTime: %s",
            $expectedEventName,
            $expectedOrgTitle,
            $expectedDescription,
            'https://example.com/event',
            'Upcoming ✅',
            'No location',
            $event->active_at->format('F j, Y g:i A T')
        );

        $this->assertEquals($expectedText, $generatedText);
    }

    /** @test */
    public function it_generates_text_with_no_venue(): void
    {
        $organization = Org::factory()->create(['title' => 'Test Organization']);

        $event = Event::factory()->create([
            'event_name' => 'Event Without Venue',
            'description' => 'Description for event without venue.',
            'organization_id' => $organization->id,
            'venue_id' => null,
            'uri' => 'https://example.com/event',
            'active_at' => now()->addDays(7),
        ]);

        $expectedText = sprintf(
            "%s\nOrganization: %s\nDescription: %s\nLink: %s\nStatus: %s\nLocation: %s\nTime: %s",
            'Event Without Venue',
            'Test Organization',
            'Description for event without venue.',
            'https://example.com/event',
            'Upcoming ✅',
            'No location',
            $event->active_at->format('F j, Y g:i A T')
        );

        $generatedText = $this->eventService->generateText($event);

        $this->assertEquals($expectedText, $generatedText);
    }

    /** @test */
    public function it_generates_text_for_past_event(): void
    {
        $organization = Org::factory()->create(['title' => 'Test Organization']);
        $event = Event::factory()->create([
            'event_name' => 'Past Event',
            'description' => 'Description for past event.',
            'organization_id' => $organization->id,
            'uri' => 'https://example.com/past-event',
            'active_at' => now()->subDays(7),
            'venue_id' => null,
        ]);

        $expectedText = sprintf(
            "%s\nOrganization: %s\nDescription: %s\nLink: %s\nStatus: %s\nLocation: %s\nTime: %s",
            'Past Event',
            'Test Organization',
            'Description for past event.',
            'https://example.com/past-event',
            'Past ✔',
            'No location',
            $event->active_at->format('F j, Y g:i A T')
        );

        $generatedText = $this->eventService->generateText($event);

        $this->assertEquals($expectedText, $generatedText);
    }

    /** @test */
    public function it_generates_text_for_cancelled_event(): void
    {
        $organization = Org::factory()->create(['title' => 'Test Organization']);
        $event = Event::factory()->create([
            'event_name' => 'Cancelled Event',
            'description' => 'Description for cancelled event.',
            'organization_id' => $organization->id,
            'uri' => 'https://example.com/cancelled-event',
            'active_at' => now()->addDays(7),
            'cancelled_at' => now(),
            'venue_id' => null,
        ]);

        $expectedText = sprintf(
            "%s\nOrganization: %s\nDescription: %s\nLink: %s\nStatus: %s\nLocation: %s\nTime: %s",
            'Cancelled Event',
            'Test Organization',
            'Description for cancelled event.',
            'https://example.com/cancelled-event',
            'Cancelled ❌',
            'No location',
            $event->active_at->format('F j, Y g:i A T')
        );

        $generatedText = $this->eventService->generateText($event);

        $this->assertEquals($expectedText, $generatedText);
    }
}
