<?php

namespace HackGreenville\EventImporter\Tests\Feature;

use App\Enums\EventServices;
use App\Models\Event;
use App\Models\Org;
use App\Models\Venue;
use HackGreenville\EventImporter\Console\Commands\ImportEventsCommand;
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

    public function test_active_eventbrite_event_is_imported_correctly(): void
    {
        Http::fake([
            'https://api.lu.ma/calendar/get-items?calendar_api_id=cal-eBssGXM4Irgjp6j&period=future&pagination_limit=50' => Http::response($this->apiResponse('response.json')),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::Luma->value,
            'service_api_key' => 'cal-eBssGXM4Irgjp6j',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $active_event = Event::query()
            ->where([
                'service' => EventServices::Luma->value,
                'service_id' => 'evt-H1KppCSH1w03RfG',
            ])
            ->firstOrFail();

        $this->assertEquals('Tech Taco Tuesday', $active_event->event_name);
        $this->assertEquals($organization->title, $active_event->group_name);
        $this->assertStringContainsString('Tech meetups and events for web developers and designers.', $active_event->description);

        $this->assertEquals(null, $active_event->rsvp_count);
        $this->assertEquals('2024-08-06 11:30:00', $active_event->active_at->toDateTimeString());
        $this->assertEquals('https://lu.ma/uxweg758', $active_event->url);
        $this->assertNull($active_event->cancelled_at);

        $this->assertEquals('upcoming', $active_event->status);

        $venue = Venue::query()->where([
            'name' => '',
            "address" => "301 Airport Rd Suite J",
            "city" => "Greenville",
            "zipcode" => "29607",
            "country" => "US",
            "lat" => "34.844795",
            "lng" => "-82.354982",
        ])
            ->firstOrFail();

        $this->assertEquals('SC', $venue->state->abbr);
    }

    protected function apiResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/luma/' . $file);
    }
}
