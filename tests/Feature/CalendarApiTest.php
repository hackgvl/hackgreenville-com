<?php

namespace Tests\Feature;

use App\Models\Event;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create known data and make an api call to make sure the event is being returned as expected.
     *
     * @return void
     * @throws Exception
     */
    public function testGetApiCalendarEvents()
    {
        $start = Carbon::now()->firstOfMonth()->toRfc3339String();
        $end   = Carbon::now()->lastOfMonth()->toRfc3339String();

        factory(Event::class)->create([
            'active_at' => $start,
            'expire_at' => $end,
        ]);

        $start = (new Carbon($start))->subMonth()->toRfc3339String();
        $end   = (new Carbon($start))->endOfMonth()->toRfc3339String();

        $response = $this->call('GET', '/api/calendar', compact('start', 'end'));

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonStructure([['start', 'end', 'title', 'description', 'allDay', 'start_fmt', 'end_fmt', 'location', 'event_id', 'event_url']]);
    }
}
