<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Tests\DatabaseTestCase;

class HomeControllerTest extends DatabaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2020-01-01');
    }

    public function test_upcoming_events_sorts_unordered_events()
    {
        Event::factory()->create([
            'event_name' => 'Event 2',
            'active_at' => '2020-01-01 19:00:00',
            'expire_at' => '2020-01-02 21:00:00',
        ]);
        Event::factory()->create([
            'event_name' => 'Event 1',
            'active_at' => '2020-01-01 18:00:00',
            'expire_at' => '2020-01-02 20:00:00',
        ]);
        Event::factory()->create([
            'event_name' => 'Event 3',
            'active_at' => '2020-01-02 12:00:00',
            'expire_at' => '2020-01-02 14:00:00',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Event 1', 'Event 2', 'Event 3']);
    }

    public function test_upcoming_events_does_not_show_old_events()
    {
        Event::factory()->create([
            'event_name' => 'Event 1',
            'active_at' => '2019-12-31 22:00:00',
            'expire_at' => '2019-12-31 23:00:00',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertDontSee('Event 1');
    }

    public function test_events_this_month_counts_multi_day_events_spanning_the_month_boundary()
    {
        // Conference that started last month but is still running this month
        Event::factory()->create([
            'active_at' => '2019-12-31 09:00:00',
            'expire_at' => '2020-01-01 17:00:00',
        ]);
        // Entirely within this month
        Event::factory()->create([
            'active_at' => '2020-01-15 18:00:00',
            'expire_at' => '2020-01-15 21:00:00',
        ]);
        // Ended last month
        Event::factory()->create([
            'active_at' => '2019-12-30 18:00:00',
            'expire_at' => '2019-12-30 21:00:00',
        ]);
        // Starts next month
        Event::factory()->create([
            'active_at' => '2020-02-01 18:00:00',
            'expire_at' => '2020-02-01 21:00:00',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $this->assertSame(2, $response->viewData('stats')['events_this_month']);
    }

    public function test_events_this_month_falls_back_to_active_at_when_expire_at_is_missing()
    {
        Event::factory()->create([
            'active_at' => '2020-01-10 18:00:00',
            'expire_at' => null,
        ]);
        Event::factory()->create([
            'active_at' => '2019-12-30 18:00:00',
            'expire_at' => null,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $this->assertSame(1, $response->viewData('stats')['events_this_month']);
    }
}
