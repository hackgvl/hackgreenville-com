<?php

namespace HackGreenville\EventImporter\Tests\Feature;

use App\Enums\EventServices;
use App\Models\Event;
use App\Models\Org;
use HackGreenville\EventImporter\Console\Commands\ImportEventsCommand;
use HackGreenville\EventImporter\Services\MeetupGraphqlExtHandler;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\DatabaseTestCase;

class MeetupGraphqlExtTest extends DatabaseTestCase
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

        Org::factory()->create([
            'service' => EventServices::MeetupGraphql,
            'service_api_key' => 'defcon864',
        ]);
    }

    public function test_meetup_event_is_imported_correctly(): void
    {
        $this->fakeHttpCalls();
        $this->runImportCommand();

        $organization = $this->getOrganization();
        $event = $this->queryEvent('302190057');

        $this->assertEquals('DEF CON 864 Meeting', $event->event_name);
        $this->assertEquals($organization->title, $event->group_name);
        $this->assertEquals("Join us for the monthly DEF CON 864 group meeting", $event->description);

        $this->assertEquals(3, $event->rsvp_count);
        $this->assertEquals(1578805140, $event->active_at->utc()->unix());
        $this->assertEquals('2020-01-11 23:59:00', $event->active_at->toDateTimeString());

        $this->assertEquals('America/New_York', $event->timezone);
        $this->assertEquals('https://www.meetup.com/defcon864/events/302190057', $event->uri);
        $this->assertEquals('2020-01-01 00:00:00', $event->cancelled_at->toDateTimeString());
        $this->assertNotNull($event->venue);
        $this->assertEquals('cancelled', $event->status);
    }

    public function test_meetup_event_venue_data_is_imported_correctly(): void
    {
        $this->fakeHttpCalls();
        $this->runImportCommand();

        $event = $this->queryEvent('302190057');
        $venue = $event->venue;

        $this->assertEquals("101 N Main St #302", $venue->address);
        $this->assertEquals("Greenville", $venue->city);
        $this->assertEquals("SC", $venue->state->abbr);
        $this->assertEquals("29601", $venue->zipcode);
        $this->assertEquals("us", $venue->country);
        $this->assertEquals(34.85202, $venue->lat);
        $this->assertEquals(-82.39968, $venue->lng);
    }

    public function test_upcoming_event_is_imported_correctly(): void
    {
        $this->fakeHttpCalls();
        $this->runImportCommand();

        $upcoming_event = $this->queryEvent('302190058');

        $this->assertNull($upcoming_event->cancelled_at);
        $this->assertEquals('upcoming', $upcoming_event->status);
    }

    public function test_cancelled_meetup_event_is_imported_correctly(): void
    {
        $this->fakeHttpCalls();
        $this->runImportCommand();

        $cancelled_event = $this->queryEvent('302190057');

        $this->assertEquals('cancelled', $cancelled_event->status);
    }

    public function test_online_event_venue_is_null(): void
    {
        $this->fakeHttpCalls();
        $this->runImportCommand();

        $event = $this->queryEvent('pwdqjtygcpbkb');

        $this->assertNotNull($event);
        $this->assertNull($event->venue);
    }

    public function test_past_meetup_event_past_max_days_not_imported(): void
    {
        $this->fakeHttpCalls();
        $this->runImportCommand();

        $event = $this->queryEvent('300699290');

        $this->assertNull($event);
    }

    public function test_upcoming_meetup_event_past_max_days_not_imported(): void
    {
        $this->fakeHttpCalls();
        $this->runImportCommand();

        $event = $this->queryEvent('pwdqjtygcqbhb');

        $this->assertNull($event);
    }

    public function test_duplicate_event_is_not_imported(): void
    {
        $this->fakeHttpCalls();
        $this->runImportCommand();

        Event::factory()->create([
            'service' => EventServices::MeetupGraphql,
            'service_id' => '306527183',
            'updated_at' => now()->subDays(1),
        ]);

        $event_original = $this->queryEvent('306527183');
        $this->assertNotNull($event_original);

        $this->assertEquals(now(), $event_original->updated_at);

        $event_duplicate = $this->queryEvent('mfuaiakmcxuzjsd');
        $this->assertNull($event_duplicate);
    }

    public function test_null_group_data_is_not_imported(): void
    {
        $eventCountBefore = Event::query()->count();

        Http::fake([
            'https://secure.meetup.com/oauth2/access' => Http::response(
                $this->apiResponse('responses/accessToken/example-access-token.json'),
                200
            ),
            'https://api.meetup.com/gql-ext' => Http::response(
                $this->apiResponse('responses/groupByUrlName/v2/example-group-null.json'),
                200
            ),
        ]);

        $this->runImportCommand();

        $eventCountAfter = Event::query()->count();

        $this->assertEquals($eventCountBefore, $eventCountAfter);
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

    private function queryEvent(string $service_id): Event|null
    {
        return Event::query()
            ->where('service', EventServices::MeetupGraphql)
            ->where('service_id', $service_id)
            ->first();
    }

    private function fakeHttpCalls(): void
    {
        Http::fake([
            'https://secure.meetup.com/oauth2/access' => Http::response(
                $this->apiResponse('responses/accessToken/example-access-token.json'),
                200
            ),
        ]);

        Http::fake([
            'https://api.meetup.com/gql-ext' => Http::response(
                $this->apiResponse('responses/groupByUrlName/v2/example-group.json'), // Example response from /gql-ext endpoint
                200
            ),
        ]);
    }
}
