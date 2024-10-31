<?php

namespace HackGreenville\EventImporter\Tests\Feature;

use App\Enums\EventServices;
use App\Models\Event;
use App\Models\Org;
use Exception;
use HackGreenville\EventImporter\Console\Commands\ImportEventsCommand;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Tests\DatabaseTestCase;

class Stub extends AbstractEventHandler
{
    // Stub implementation for testing
    protected function mapIntoEventData(array $data): EventData
    {
        $service_name = config('event-import-handlers.service_name');
        $service_id = config('event-import-handlers.service_id');

        return EventData::from([
            'service' => $service_name,
            'service_id' => $service_id,
            'name' => 'Stub Event',
            'description' => 'This is a stub event for testing.',
            'url' => 'http://example.com/stub-event',
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addDays(1),
            'timezone' => 'UTC',
            'cancelled_at' => null,
            'rsvp' => 0,
            'venue' => null,
        ]);
    }

    protected function mapIntoVenueData(array $data): ?VenueData
    {
        return null;
    }

    protected function eventResults(int $page): Collection
    {
        return collect([[]]); // return non-empty array to simulate events
    }
}

class ExceptionStub extends AbstractEventHandler
{
    // Simulate an exception thrown during event data mapping
    protected function mapIntoEventData(array $data): EventData
    {
        throw new Exception('Test exception');
    }

    protected function mapIntoVenueData(array $data): ?VenueData
    {
        return null;
    }

    protected function eventResults(int $page): Collection
    {
        return collect([[]]); // return non-empty array to simulate events
    }
}

class ImportEventsCommandTest extends DatabaseTestCase
{
    public const MEETUP_REST = EventServices::MeetupRest->value;
    public const MEETUP_GRAPHQL = EventServices::MeetupGraphql->value;
    public const EVENTBRITE = EventServices::EventBrite->value;

    public const SERVICE_ID = '12345';
    public const ALT_SERVICE_ID = 'foobar';

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2020-01-15');
    }

    public function test_new_event_is_imported(): void
    {
        $this->setServiceValues(self::MEETUP_REST, self::SERVICE_ID);

        $this->runImportCommand();

        $result = $this->queryEvent(self::SERVICE_ID);
        $this->assertNotNull($result);
    }

    public function test_imported_event_updates_event_with_same_service_id(): void
    {
        $this->setServiceValues(self::MEETUP_REST, self::SERVICE_ID);

        $this->createEvent(0, self::SERVICE_ID);

        $this->runImportCommand();

        $result = $this->queryEvent(self::SERVICE_ID);
        $this->assertNotNull($result);
        $this->assertEquals('Stub Event', $result->event_name);
    }

    public function test_event_for_meetup_rest_org_at_same_time_different_service_id_is_deleted(): void
    {
        $this->setServiceValues(self::MEETUP_REST, self::SERVICE_ID);

        $this->createEvent(0, self::ALT_SERVICE_ID);

        $this->runImportCommand();

        $result = $this->queryEvent(self::ALT_SERVICE_ID);
        $this->assertNull($result);
    }

    public function test_event_for_meetup_graphql_org_at_same_time_different_service_id_is_deleted(): void
    {
        $this->setServiceValues(self::MEETUP_GRAPHQL, self::SERVICE_ID);

        $this->createEvent(0, self::ALT_SERVICE_ID);

        $this->runImportCommand();

        $result = $this->queryEvent(self::ALT_SERVICE_ID);
        $this->assertNull($result);
    }

    public function test_event_for_org_at_different_time_different_service_id_is_not_deleted(): void
    {
        $this->setServiceValues(self::MEETUP_REST, self::SERVICE_ID);

        $this->createEvent(1, self::ALT_SERVICE_ID);

        $this->runImportCommand();

        $result = $this->queryEvent(self::ALT_SERVICE_ID);
        $this->assertNotNull($result);
    }

    public function test_event_for_non_meetup_at_same_time_different_service_id_is_not_deleted(): void
    {
        $this->setServiceValues(self::EVENTBRITE, self::SERVICE_ID);

        $this->createEvent(1, self::ALT_SERVICE_ID);

        $this->runImportCommand();

        $result = $this->queryEvent(self::ALT_SERVICE_ID);
        $this->assertNotNull($result);
    }

    public function test_event_for_different_org_is_not_deleted(): void
    {
        $this->setServiceValues(self::MEETUP_REST, self::SERVICE_ID);

        $org = Org::factory()->create();
        $this->createEvent(1, self::ALT_SERVICE_ID, $org->id);

        $this->runImportCommand();

        $result = $this->queryEvent(self::ALT_SERVICE_ID);
        $this->assertNotNull($result);
    }

    public function test_event_import_on_exception_does_not_delete_records(): void
    {
        $this->setServiceValues(self::MEETUP_REST, self::SERVICE_ID);

        config()->set('event-import-handlers.handlers', [
            $this->getServiceName() => ExceptionStub::class,
        ]);

        $this->createEvent(0, self::ALT_SERVICE_ID);

        $this->expectException(Exception::class);

        $this->runImportCommand();

        $result = $this->queryEvent(self::ALT_SERVICE_ID);
        $this->assertNotNull($result);
    }

    private function createEvent(int $minutesToAdd, string $service_id, int $org_id = 1): void
    {
        $service_name = $this->getServiceName();

        Event::factory()->create([
            'organization_id' => $org_id,
            'service' => $service_name,
            'service_id' => $service_id,
            'event_name' => 'Existing Event',
            'active_at' => Carbon::now()->addMinutes($minutesToAdd),
        ]);
    }

    private function setServiceValues(string $service_name, string $service_id): void
    {
        config()->set('event-import-handlers.service_name', $service_name);
        config()->set('event-import-handlers.service_id', $service_id);

        config()->set('event-import-handlers.handlers', [
            $service_name => Stub::class,
        ]);
        config()->set('event-import-handlers.active_services', [
            $service_name
        ]);

        Org::factory()->create([
            'service' => $service_name,
            'service_api_key' => '123456789',
        ]);
    }

    private function getServiceId(): string
    {
        return config('event-import-handlers.service_id');
    }

    private function getServiceName(): string
    {
        return config('event-import-handlers.service_name');
    }

    private function queryEvent(string $service_id): Event | null
    {
        return Event::query()->where('service_id', $service_id)->first();
    }

    private function runImportCommand(): void
    {
        $this->artisan(ImportEventsCommand::class);
    }
}
