<?php

namespace HackGreenville\EventImporter\Tests\Feature;

use App\Enums\EventServices;
use App\Models\Event;
use App\Models\Org;
use App\Models\Venue;
use HackGreenville\EventImporter\Console\Commands\ImportEventsCommand;
use HackGreenville\EventImporter\Services\LumaHandler;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\DatabaseTestCase;

class LumaTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2020-01-01');
    }

    public function test_active_luma_event_is_imported_correctly(): void
    {
        Http::fake([
            'https://api.lu.ma/calendar/get-items?calendar_api_id=cal-eBssGXM4Irgjp6j&period=future&pagination_limit=50' => Http::response($this->apiResponse('response.json')),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::Luma->value,
            'service_api_key' => 'cal-eBssGXM4Irgjp6j',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $event1 = Event::query()
            ->where([
                'service' => EventServices::Luma->value,
                'service_id' => 'evt-Q4H602NTIQhpdMv',
            ])
            ->firstOrFail();

        $this->assertEquals('Furman Makerspace Field Trip', $event1->event_name);
        $this->assertEquals($organization->title, $event1->group_name);
        $this->assertStringContainsString('This is a test Luma event', $event1->description);

        $this->assertEquals(1, $event1->rsvp_count);
        $this->assertEquals('2025-07-12 12:30:00', $event1->active_at->toDateTimeString());
        $this->assertEquals('America/New_York', $event1->timezone);
        $this->assertEquals('https://lu.ma/zadco7op', $event1->url);
        $this->assertNull($event1->cancelled_at);

        $this->assertEquals('upcoming', $event1->status);

        $venue = Venue::query()->where([
            'name' => 'Furman University',
        ])
            ->firstOrFail();

        $this->assertEquals('3300 Poinsett Hwy', $venue->address);
        $this->assertEquals('Greenville', $venue->city);
        $this->assertEquals('SC', $venue->state->abbr);
        $this->assertEquals('29613', $venue->zipcode);
        $this->assertEquals('US', $venue->country);
        $this->assertEquals('34.9274688', $venue->lat);
        $this->assertEquals('-82.4400666', $venue->lng);

        // Virtual Event
        $event2 = Event::query()
            ->where([
                'service' => EventServices::Luma->value,
                'service_id' => 'evt-RpMfSSJJq9u7mnd',
            ])
            ->firstOrFail();
        $this->assertNull($event2->venue);
    }

    protected function apiResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/luma/' . $file);
    }
}
