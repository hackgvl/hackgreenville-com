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
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2020-01-01');

        config(['services.eventbrite.private_token' => 'ABC']);
        config(['event-import-handlers.max_days_in_past' => 30]);
        config(['event-import-handlers.max_days_in_future' => 180]);
    }

    public function test_active_eventbrite_event_is_imported_correctly(): void
    {
        Http::fake([
            $this->getEventbriteUrl('15516951616') => Http::response($this->apiResponse('live-event-in-past.json')),
            'https://www.eventbriteapi.com/v3/venues/21742454?token=ABC' => Http::response(
                $this->apiResponse('live-event-in-past-venue.json')
            ),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::EventBrite->value,
            'service_api_key' => '15516951616',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $active_event = Event::query()
            ->where([
                'service' => EventServices::EventBrite->value,
                'service_id' => '39146789100',
            ])
            ->firstOrFail();

        $this->assertEquals('974c9c735567f0160b9a7df25e1837c9', $active_event->event_uuid);
        $this->assertEquals('BSides Greenville 2018', $active_event->event_name);
        $this->assertEquals($organization->title, $active_event->group_name);
        $this->assertStringContainsString('Security BSides is coming to Greenville', $active_event->description);

        $this->assertEquals(null, $active_event->rsvp_count);
        $this->assertEquals('2018-03-10 08:30:00', $active_event->active_at->toDateTimeString());
        $this->assertEquals('2018-03-10 17:30:00', $active_event->expire_at->toDateTimeString());
        $this->assertEquals('https://www.eventbrite.com/e/bsides-greenville-2018-tickets-39146789100', $active_event->url);
        $this->assertNull($active_event->cancelled_at);

        $this->assertEquals('past', $active_event->status);

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

    public function test_cancelled_eventbrite_event_is_imported_correctly(): void
    {
        Http::fake([
            $this->getEventbriteUrl('36673227093') => Http::response($this->apiResponse('cancelled-event.json')),
            'https://www.eventbriteapi.com/v3/venues/21742454?token=ABC' => Http::response(
                $this->apiResponse('live-event-in-past-venue.json')
            ),
        ]);
        $organization = Org::factory()->create([
            'service' => EventServices::EventBrite->value,
            'service_api_key' => '36673227093',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $event = Event::query()
            ->where([
                'service' => EventServices::EventBrite->value,
                'service_id' => '478619482757',
            ])
            ->firstOrFail();

        $cancelled_event = Event::query()->where('event_uuid', '4510a60271a2eb0bb01847268607366d')->firstOrFail();

        $this->assertEquals('cancelled', $cancelled_event->status);
        $this->assertNotNull($cancelled_event->cancelled_at);
    }

    protected function getEventbriteUrl(string $service_api_key): string
    {
        return 'https://www.eventbriteapi.com/v3/organizers/' . $service_api_key .
        '/events/?token=ABC&status=all&order_by=start_desc&expand=event_sales_status' .
        '&start_date.range_start=2019-12-02T00%3A00%3A00&start_date.range_end=2020-06-29T00%3A00%3A00';
    }

    protected function apiResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/eventbrite/' . $file);
    }
}
