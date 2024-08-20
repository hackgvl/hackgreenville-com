<?php

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
        ]);
        Event::factory()->create([
            'event_name' => 'Event 1',
            'active_at' => '2020-01-01 18:00:00',
        ]);
        Event::factory()->create([
            'event_name' => 'Event 3',
            'active_at' => '2020-01-02 12:00:00',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Event 1', 'Event 2', 'Event 3']);
    }

    public function test_upcoming_events_does_not_show_old_events()
    {
        Event::factory()->create([
            'event_name' => 'Event 1',
            'active_at' => '2019-12-31 23:59:59',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertDontSee('Event 1');
    }
}
