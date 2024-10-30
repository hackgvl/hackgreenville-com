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
    public static string $service = EventServices::EventBrite->value;
    public static string $service_id = 'stub_id';

    // Stub implementation for testing
    protected function mapIntoEventData(array $data): EventData
    {
        return EventData::from([
            'service' => self::$service,
            'service_id' => self::$service_id,
            'name' => 'Stub Event',
            'description' => 'This is a stub event for testing.',
            'url' => 'http://example.com/stub-event',
            'starts_at' => Carbon::now()->addDays(1),
            'ends_at' => Carbon::now()->addDays(2),
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
    public static string $service = EventServices::EventBrite->value;
    public static string $service_id = 'stub_id';

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
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2020-01-15');

        config()->set('event-import-handlers.max_days_in_past', 5);
        config()->set('event-import-handlers.max_days_in_future', 5);
        config()->set('event-import-handlers.handlers', [
            Stub::$service => Stub::class,
        ]);
        config()->set('event-import-handlers.active_services', [
            Stub::$service
        ]);

        Org::factory()->create([
            'service' => Stub::$service,
            'service_api_key' => '123456789',
        ]);
    }

    public function test_new_event_is_imported(): void
    {
        $this->runImportCommand();

        $result = $this->queryEvent(Stub::$service_id);
        $this->assertNotNull($result);
    }

    public function test_old_event_outside_days_in_past_not_deleted(): void
    {
        $this->createEvent(-6, 'foobar');

        $this->runImportCommand();

        $result = $this->queryEvent('foobar');
        $this->assertNotNull($result);
    }

    public function test_future_event_outside_days_in_future_not_deleted(): void
    {
        $this->createEvent(6, 'foobar');

        $this->runImportCommand();

        $result = $this->queryEvent('foobar');
        $this->assertNotNull($result);
    }

    public function test_imported_event_with_service_id_is_updated(): void
    {
        $this->createEvent(0, Stub::$service_id);

        $this->runImportCommand();

        $result = $this->queryEvent(Stub::$service_id);
        $this->assertNotNull($result);
        $this->assertEquals('Stub Event', $result->event_name);
    }

    public function test_event_in_date_range_not_imported_is_deleted(): void
    {
        $this->createEvent(1, 'foobar');

        $this->runImportCommand();

        $result = $this->queryEvent('foobar');
        $this->assertNull($result);
    }

    public function test_event_for_different_org_is_not_deleted(): void
    {
        $org = Org::factory()->create();
        $this->createEvent(1, 'foobar', $org->id);

        $this->runImportCommand();

        $result = $this->queryEvent('foobar');
        $this->assertNotNull($result);
    }

    public function test_event_import_on_exception_does_not_delete_records(): void
    {
        config()->set('event-import-handlers.handlers', [
            ExceptionStub::$service => ExceptionStub::class,
        ]);

        $this->createEvent(1, 'foobar');

        $this->expectException(Exception::class);

        $this->runImportCommand();

        $result = $this->queryEvent('foobar');
        $this->assertNotNull($result);
    }

    private function createEvent(int $daysToAdd, string $service_id, int $org_id = 1): void
    {
        Event::factory()->create([
            'organization_id' => $org_id,
            'service' => Stub::$service,
            'service_id' => $service_id,
            'event_name' => 'Existing Event',
            'active_at' => Carbon::now()->addDays($daysToAdd),
        ]);
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
