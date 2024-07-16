<?php

namespace HackGreenville\EventImporter\Tests\Feature;

use App\Enums\EventServices;
use App\Models\Event;
use App\Models\Org;
use HackGreenville\EventImporter\Console\Commands\ImportEventsCommand;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\DatabaseTestCase;

class MeetupGraphqlTest extends DatabaseTestCase
{
    public function test_active_meetup_event_is_imported_correctly(): void
    {
        $this->setupTestDate();

        Http::fake([
            $this->getMeetupUrl() => Http::response(
                $this->apiResponse('example-group.json'),
                200
            ),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::MeetupGraphql,
            'service_api_key' => 'defcon864',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $active_event = $this->queryEvent('301411834');

        $this->assertEquals('Build Carolina: empowering tech professionals in South Carolina through training', $active_event->event_name);
        $this->assertEquals($organization->title, $active_event->group_name);
        $this->assertStringContainsString("Lauren McGlamery will share the mission of Build Carolina as a tech talent hub, fostering a vibrant community for tech professionals. We'll explore the various ways we achieve this, including:\n\n* " .
        "Comprehensive training programs: We offer a range of training programs to address the industry's ever-evolving needs.\n* Dedicated support: We offer career guidance, mentorship opportunities, and other resources to empower aspiring tech " .
        "professionals.\n* Supportive community for the tech ecosystem in SC: Our organization provides a platform for collaboration, knowledge sharing, and professional development.\n* Learning opportunities and giving back initiatives: We'll showcase ways " .
        "experienced professionals can continue to expand their skills and contribute their knowledge and expertise to the community.\n\nWe'll also delve into our unique apprenticeship program, designed to bridge the gap between theory and practice for " .
        "early-career professionals in any tech-based role. Learn how your organization can benefit from partnering with Build Carolina to build a robust tech workforce.\n\n**Agenda**\n\n" .
        "1. Welcome & Announcements\n2. Presentation (*above*)\n3. Projects & Hobbies\n4. Networking", $active_event->description);

        $this->assertEquals(19, $active_event->rsvp_count);
        $this->assertEquals(1720735200, $active_event->active_at->utc()->unix());
        $this->assertEquals('https://www.meetup.com/defcon864/events/301411834', $active_event->uri);
        $this->assertNull($active_event->cancelled_at);
        $this->assertEquals('upcoming', $active_event->status);
    }

    public function test_meetup_event_venue_data_is_imported_correctly(): void
    {
        $this->setupTestDate();

        Http::fake([
            $this->getMeetupUrl() => Http::response(
                $this->apiResponse('example-group.json'),
                200
            ),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::MeetupGraphql,
            'service_api_key' => 'defcon864',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $event = $this->queryEvent('301411834');
        $venue = $event->venue;

        $this->assertEquals("101 N Main St #302", $venue->address);
        $this->assertEquals("Greenville", $venue->city);
        $this->assertEquals("SC", $venue->state->abbr);
        $this->assertEquals("29601", $venue->zipcode);
        $this->assertEquals("us", $venue->country);
        $this->assertEquals(34.85202, $venue->lat);
        $this->assertEquals(-82.39968, $venue->lng);
    }

    public function test_cancelled_meetup_event_is_imported_correctly(): void
    {
        $this->setupTestDate();

        Http::fake([
            $this->getMeetupUrl() => Http::response(
                $this->apiResponse('example-group.json'),
                200
            ),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::MeetupGraphql,
            'service_api_key' => 'defcon864',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $cancelled_event = $this->queryEvent('302190057');

        $this->assertEquals('cancelled', $cancelled_event->status);
    }

    public function test_past_meetup_event_is_imported_correctly(): void
    {
        $this->setupTestDate();

        Http::fake([
            $this->getMeetupUrl() => Http::response(
                $this->apiResponse('example-group.json'),
                200
            ),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::MeetupGraphql,
            'service_api_key' => 'defcon864',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $past_event = $this->queryEvent('301559297');

        $this->assertEquals('past', $past_event->status);
    }

    protected function getMeetupUrl(): string
    {
        return 'https://api.meetup.com/gql';
    }

    protected function apiResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/meetup-graphql/' . $file);
    }

    private function setupTestDate(): void
    {
        Carbon::setTestNow('2020-01-01');
    }

    private function queryEvent(string $service_id): Event
    {
        return Event::query()
            ->where('service', EventServices::MeetupGraphql)
            ->where('service_id', $service_id)
            ->firstOrFail();
    }
}
