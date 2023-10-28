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

class EventBriteTest extends DatabaseTestCase
{
    public function test_meet_up_event_is_imported_correctly(): void
    {
        Carbon::setTestNow('2020-01-01');

        config(['services.eventbrite.private_token' => 'ABC']);

        Http::fake([
            'https://www.eventbriteapi.com/v3/organizers/15516951616/events/?token=ABC&status=all&order_by=start_desc&start_date.range_start=2019-12-01T00%3A00%3A00&start_date.range_end=2020-04-01T00%3A00%3A00' => Http::response($this->apiResponse('live-event-in-past.json')),
            'https://www.eventbriteapi.com/v3/venues/21742454?token=ABC' => Http::response($this->apiResponse('live-event-in-past-venue.json')),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::EventBrite->value,
            'service_api_key' => '15516951616',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $event = Event::query()
            ->where([
                'service' => EventServices::EventBrite->value,
                'service_id' => '39146789100',
            ])
            ->firstOrFail();

        $this->assertEquals('974c9c735567f0160b9a7df25e1837c9', $event->event_uuid);
        $this->assertEquals('BSides Greenville 2018', $event->event_name);
        $this->assertEquals($organization->title, $event->group_name);
        $this->assertStringContainsString('Security BSides is coming to Greenville', $event->description);

        $this->assertEquals(null, $event->rsvp_count);
        $this->assertEquals('2018-03-10 08:30:00', $event->active_at->toDateTimeString());
        $this->assertEquals('https://www.eventbrite.com/e/bsides-greenville-2018-tickets-39146789100', $event->url);
        $this->assertNull($event->cancelled_at);

        $this->assertEquals('past', $event->status);

        $venue = Venue::query()->where([
            'name' => 'Fluor Management Center',
            "address" => "100 Fluor Daniel Drive",
            "city" => "Greenville",
            "zipcode" => "29607",
            "country" => "US",
            "lat" => "34.8442668",
            "lng" => "-82.3340627",
        ])
            ->firstOrFail();

        $this->assertEquals('SC', $venue->state->abbr);
    }

    protected function apiResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/eventbrite/' . $file);
    }
}
