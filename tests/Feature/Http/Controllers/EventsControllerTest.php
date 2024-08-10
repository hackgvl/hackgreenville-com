<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use App\Models\Org;
use Carbon\Carbon;
use Tests\DatabaseTestCase;

class EventsControllerTest extends DatabaseTestCase
{
  function setUp(): void {
    parent::setUp();

    Carbon::setTestNow('2020-01-01');

    Event::factory()->create([
      'event_name' => 'Event 2',
      'active_at' => '2020-01-02 19:00:00',
    ]);
    Event::factory()->create([
      'event_name' => 'Event 1',
      'active_at' => '2020-01-02 18:00:00',
    ]);
    Event::factory()->create([
      'event_name' => 'Event 3',
      'active_at' => '2020-01-02 20:00:00',
    ]);
  }

  function test_home_controller_index_method()
  {
    $response = $this->get(route('events.index'));

    $response->assertStatus(200);
    $response->assertSeeInOrder(['Event 1', 'Event 2', 'Event 3']);
  }
}
