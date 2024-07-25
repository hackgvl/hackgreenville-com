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
                'service_id' => 'evt-H1KppCSH1w03RfG',
                'event_name' => 'Tech Taco Tuesday',
            ])
            ->firstOrFail();

        $this->assertEquals($organization->title, $event1->group_name);
        $this->assertStringContainsString('Tech meetups and events for web developers and designers.', $event1->description);

        $this->assertEquals(null, $event1->rsvp_count);
        $this->assertEquals('2024-08-06 11:30:00', $event1->active_at->toDateTimeString());
        $this->assertEquals('https://lu.ma/uxweg758', $event1->url);
        $this->assertNull($event1->cancelled_at);

        $this->assertEquals('upcoming', $event1->status);

        $venue = Venue::query()->where([
            'name' => 'White Duck Taco Shop',
            "address" => "301 Airport Rd Suite J",
            "city" => "Greenville",
            "zipcode" => "29607",
            "country" => "US",
            "lat" => "34.844795",
            "lng" => "-82.354982",
        ])
            ->firstOrFail();

        $this->assertEquals('SC', $venue->state->abbr);


        // Event 2
        $event2 = Event::query()
            ->where([
                'service' => EventServices::Luma->value,
                'service_id' => 'evt-pDFUoxEnWDdJw7F',
                'event_name' => 'Tech Taco Tuesday - second event',
            ])
            ->firstOrFail();

        $this->assertEquals($organization->title, $event2->group_name);
        $this->assertStringContainsString('Tech meetups and events for web developers and designers.', $event2->description);

        $this->assertEquals(null, $event2->rsvp_count);
        $this->assertEquals('2024-09-03 11:30:00', $event2->active_at->toDateTimeString());
        $this->assertEquals('2024-09-03 12:30:00', $event2->expire_at->toDateTimeString());
        $this->assertEquals('https://lu.ma/0w9vm5fw', $event2->url);
        $this->assertNull($event2->cancelled_at);

        $this->assertEquals('upcoming', $event2->status);

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

        // Virtual Event

        $event2 = Event::query()
            ->where([
                'service' => EventServices::Luma->value,
                'service_id' => 'evt-VDjbDc3iEH6r3r7',
                'event_name' => 'zoom event',
            ])
            ->firstOrFail();

        $this->assertEquals($organization->title, $event2->group_name);
        $this->assertStringContainsString('hello world', $event2->description);

        $this->assertEquals('2024-07-19 21:30:00', $event2->active_at->toDateTimeString());
        $this->assertEquals('2024-07-19 22:30:00', $event2->expire_at->toDateTimeString());
        $this->assertEquals('https://lu.ma/n9gijqux', $event2->url);
        $this->assertNull($event2->cancelled_at);
        $this->assertEquals('upcoming', $event2->status);
    }

    protected function apiResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/luma/' . $file);
    }
}
