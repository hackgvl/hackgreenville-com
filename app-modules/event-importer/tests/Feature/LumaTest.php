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
            LumaHandler::$LUMA_API_BASE_URL . '/calendar/get-items?calendar_api_id=cal-eBssGXM4Irgjp6j&period=future&pagination_limit=50' => Http::response($this->apiResponse('response.json')),
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
        $this->assertEquals('https://lu.ma/zadco7op', $event1->url());
        $this->assertNull($event1->cancelled_at);

        $this->assertEquals('upcoming', $event1->status());

        $venue = Venue::query()->where([
            'name' => 'Furman University',
        ])
            ->firstOrFail();

        $this->assertEquals('3300 Poinsett Hwy', $venue->address);
        $this->assertEquals('Greenville', $venue->city);
        $this->assertEquals('SC', $venue->state);
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

    public function test_external_event_is_not_imported(): void
    {
        Http::fake([
            LumaHandler::$LUMA_API_BASE_URL . '/calendar/get-items?calendar_api_id=cal-jLDJBTnVF9QGY6V&period=future&pagination_limit=50' => Http::response($this->apiResponse('response-external-event.json')),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::Luma->value,
            'service_api_key' => 'cal-jLDJBTnVF9QGY6V',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $event = Event::query()
            ->where([
                'service' => EventServices::Luma->value,
                'organization_id' => $organization->id,
            ])
            ->first();

        $this->assertNull($event);
    }

    public function test_not_manager_event_is_not_imported(): void
    {
        Http::fake([
            LumaHandler::$LUMA_API_BASE_URL . '/calendar/get-items?calendar_api_id=cal-jLDJBTnVF9QGY6V&period=future&pagination_limit=50' => Http::response($this->apiResponse('response-not-manager-event.json')),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::Luma->value,
            'service_api_key' => 'cal-jLDJBTnVF9QGY6V',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $event = Event::query()
            ->where([
                'service' => EventServices::Luma->value,
                'organization_id' => $organization->id
            ])
            ->first();

        $this->assertNull($event);
    }

    public function test_real_gvl_game_devs_addresses_use_the_parsed_fields(): void
    {
        Http::fake([
            LumaHandler::$LUMA_API_BASE_URL . '/calendar/get-items?calendar_api_id=cal-D6zd3wYHOTe4hXl&period=future&pagination_limit=50' => Http::response($this->apiResponse('response-gvl-game-devs.json')),
        ]);

        $unnamed = Venue::factory()->create([
            'name' => '',
            'slug' => null,
            'address' => '101 N Main St',
            'city' => 'Greenville',
            'state' => 'SC',
            'zipcode' => '29601',
            'country' => 'US',
            'lat' => '34.8516838',
            'lng' => '-82.3998468',
        ]);

        Org::factory()->create([
            'service' => EventServices::Luma->value,
            'service_api_key' => 'cal-D6zd3wYHOTe4hXl',
        ]);

        $this->artisan(ImportEventsCommand::class);
        $this->artisan(ImportEventsCommand::class);

        $named = Event::query()->where('service_id', 'evt-s5BZ4W5NbLSiR0E')->firstOrFail();
        $streetOnly = Event::query()->where('service_id', 'evt-kxhmuNG9mRkOetF')->firstOrFail();

        $this->assertSame('SynergyMill', $named->venue->name);
        $this->assertSame('synergymill', $named->venue->slug);
        $this->assertSame('31 Cessna Ct Ste B', $named->venue->address);
        $this->assertSame('Greenville', $named->venue->city);
        $this->assertSame('SC', $named->venue->state);
        $this->assertSame('29607', $named->venue->zipcode);
        $this->assertSame('US', $named->venue->country);
        $this->assertEquals('34.8484931', $named->venue->lat);
        $this->assertEquals('-82.3431733', $named->venue->lng);

        $this->assertFalse($unnamed->is($streetOnly->venue));
        $this->assertFalse($named->venue->is($streetOnly->venue));
        $this->assertSame('31 Cessna Ct', $streetOnly->venue->name);
        $this->assertSame('31-cessna-ct', $streetOnly->venue->slug);
        $this->assertSame('31 Cessna Ct', $streetOnly->venue->address);
        $this->assertSame('Greenville', $streetOnly->venue->city);
        $this->assertSame('SC', $streetOnly->venue->state);
        $this->assertSame('29607', $streetOnly->venue->zipcode);
        $this->assertSame('US', $streetOnly->venue->country);

        $this->assertSame('', $unnamed->fresh()->name);
        $this->assertSame('101 N Main St', $unnamed->fresh()->address);
        $this->assertSame(0, $unnamed->events()->count());
        $this->assertSame(3, Venue::query()->count());
    }

    public function test_parsed_luma_fields_fill_the_venue_and_fall_back_when_omitted(): void
    {
        Http::fake([
            LumaHandler::$LUMA_API_BASE_URL . '/calendar/get-items?calendar_api_id=cal-parsed-fallbacks&period=future&pagination_limit=50' => Http::response($this->apiResponse('response-parsed-fallbacks.json')),
        ]);

        Org::factory()->create([
            'service' => EventServices::Luma->value,
            'service_api_key' => 'cal-parsed-fallbacks',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $fallback = Event::query()->where('service_id', 'evt-missing-parsed')->firstOrFail();
        $this->assertSame('Pangaea Brewing', $fallback->venue->name);
        $this->assertSame('115 Welborn St Suite A', $fallback->venue->address);
        $this->assertSame('Greenville', $fallback->venue->city);
        $this->assertSame('SC', $fallback->venue->state);
        $this->assertSame('29601', $fallback->venue->zipcode);
        $this->assertSame('US', $fallback->venue->country);

        $cityOnly = Event::query()->where('service_id', 'evt-city-only')->firstOrFail();
        $this->assertSame('Greenville', $cityOnly->venue->name);
        $this->assertNull($cityOnly->venue->address);
        $this->assertSame('Greenville', $cityOnly->venue->city);
        $this->assertSame('SC', $cityOnly->venue->state);
        $this->assertNull($cityOnly->venue->zipcode);
        $this->assertSame('US', $cityOnly->venue->country);

        $extraLine = Event::query()->where('service_id', 'evt-short-extra')->firstOrFail();
        $this->assertSame('Flywheel Coworking Greenville', $extraLine->venue->name);
        $this->assertSame('Furman Innovation Lab, 25 Goldsmith St', $extraLine->venue->address);
        $this->assertSame('Greenville', $extraLine->venue->city);
        $this->assertSame('SC', $extraLine->venue->state);
        $this->assertSame('29609', $extraLine->venue->zipcode);
        $this->assertSame('US', $extraLine->venue->country);
    }

    protected function apiResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/luma/' . $file);
    }
}
