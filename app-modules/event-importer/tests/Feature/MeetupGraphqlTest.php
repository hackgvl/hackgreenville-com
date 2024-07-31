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
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2020-01-01');

        config()->set('event-import-handlers.max_days_in_past', 10);
        config()->set('event-import-handlers.max_days_in_future', 10);

        config()->set('event-import-handlers.meetup_graphql_private_key_path', __DIR__ . '/../fixtures/meetup-graphql/test_key.pem');
        config()->set('event-import-handlers.meetup_graphql_client_id', 'foo');
        config()->set('event-import-handlers.meetup_graphql_member_id', 'bar');
        config()->set('event-import-handlers.meetup_graphql_private_key_id', 'abc123');

        Http::fake([
            'https://secure.meetup.com/oauth2/access' => Http::response(
                $this->apiResponse('example-access-token.json'),
                200
            ),
        ]);

        Http::fake([
            'https://api.meetup.com/gql' => Http::response(
                $this->apiResponse('example-group.json'),
                200
            ),
        ]);

        Org::factory()->create([
            'service' => EventServices::MeetupGraphql,
            'service_api_key' => 'defcon864',
        ]);
    }

    public function test_meetup_event_is_imported_correctly(): void
    {
        $this->runImportCommand();

        $organization = $this->getOrganization();
        $event = $this->queryEvent('301411834');

        $this->assertEquals('Build Carolina: empowering tech professionals in South Carolina through training', $event->event_name);
        $this->assertEquals($organization->title, $event->group_name);
        $this->assertStringContainsString("Lauren McGlamery will share the mission of Build Carolina as a tech talent hub, fostering a vibrant community for tech professionals. We'll explore the various ways we achieve this, including:\n\n* " .
        "Comprehensive training programs: We offer a range of training programs to address the industry's ever-evolving needs.\n* Dedicated support: We offer career guidance, mentorship opportunities, and other resources to empower aspiring tech " .
        "professionals.\n* Supportive community for the tech ecosystem in SC: Our organization provides a platform for collaboration, knowledge sharing, and professional development.\n* Learning opportunities and giving back initiatives: We'll showcase ways " .
        "experienced professionals can continue to expand their skills and contribute their knowledge and expertise to the community.\n\nWe'll also delve into our unique apprenticeship program, designed to bridge the gap between theory and practice for " .
        "early-career professionals in any tech-based role. Learn how your organization can benefit from partnering with Build Carolina to build a robust tech workforce.\n\n**Agenda**\n\n" .
        "1. Welcome & Announcements\n2. Presentation (*above*)\n3. Projects & Hobbies\n4. Networking", $event->description);

        $this->assertEquals(19, $event->rsvp_count);
        $this->assertEquals(1577833200, $event->active_at->utc()->unix());
        $this->assertEquals('https://www.meetup.com/defcon864/events/301411834', $event->uri);
        $this->assertNull($event->cancelled_at);
        $this->assertNotNull($event->venue);
        $this->assertEquals('past', $event->status);
    }

    public function test_meetup_event_venue_data_is_imported_correctly(): void
    {
        $this->runImportCommand();

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
        $this->runImportCommand();

        $cancelled_event = $this->queryEvent('302190057');

        $this->assertEquals('cancelled', $cancelled_event->status);
    }

    public function test_past_meetup_event_is_imported_correctly(): void
    {
        $this->runImportCommand();

        $past_event = $this->queryEvent('301559297');

        $this->assertEquals('past', $past_event->status);
    }

    public function test_online_event_venue_is_null(): void
    {
        $this->runImportCommand();

        $event = $this->queryEvent('pwdqjtygcpbkb');

        $this->assertNull($event->venue);
    }

    public function test_past_meetup_event_past_max_days_not_imported(): void
    {
        $this->runImportCommand();

        $event = $this->queryEvent('300699290');

        $this->assertNull($event);
    }

    public function test_upcoming_meetup_event_past_max_days_not_imported(): void
    {
        $this->runImportCommand();

        $event = $this->queryEvent('pwdqjtygcqbhb');

        $this->assertNull($event);
    }

    public function test_config_validation_fails_with_missing_client_id(): void
    {
        config()->set('event-import-handlers.meetup_graphql_client_id', null);

        $this->expectExceptionMessage('meetup_graphql_client_id config value must be set.');

        $this->runImportCommand();
    }

    public function test_config_validation_fails_with_missing_member_id(): void
    {
        config()->set('event-import-handlers.meetup_graphql_member_id', null);

        $this->expectExceptionMessage('meetup_graphql_member_id config value must be set.');

        $this->runImportCommand();
    }

    public function test_config_validation_fails_with_missing_private_key_id(): void
    {
        config()->set('event-import-handlers.meetup_graphql_private_key_id', null);

        $this->expectExceptionMessage('meetup_graphql_private_key_id config value must be set.');

        $this->runImportCommand();
    }

    public function test_config_validation_fails_with_missing_private_key_path(): void
    {
        config()->set('event-import-handlers.meetup_graphql_private_key_path', null);

        $this->expectExceptionMessage('meetup_graphql_private_key_path config value must be set.');

        $this->runImportCommand();
    }

    public function test_file_path_validation_fails_when_private_key_path_does_not_exist(): void
    {
        $file_path = __DIR__ . '/../fixtures/meetup-graphql/file_does_not_exist.pem';
        config()->set('event-import-handlers.meetup_graphql_private_key_path', $file_path);

        $this->expectExceptionMessage('File path ' . $file_path . ' does not exist.');

        $this->runImportCommand();
    }

    protected function apiResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/meetup-graphql/' . $file);
    }

    private function runImportCommand(): void
    {
        $this->artisan(ImportEventsCommand::class);
    }

    private function getOrganization(): Org
    {
        return Org::query()
            ->where('service', EventServices::MeetupGraphql)
            ->where('service_api_key', 'defcon864')
            ->firstOrFail();
    }

    private function queryEvent(string $service_id): Event | null
    {
        return Event::query()
            ->where('service', EventServices::MeetupGraphql)
            ->where('service_id', $service_id)
            ->first();
    }
}
