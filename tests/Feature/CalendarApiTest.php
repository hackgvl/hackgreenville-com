<?php

namespace Tests\Feature;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('calendar api returns events', function () {
    {
        $start = Carbon::now()->firstOfMonth()->addDays(3)->toRfc3339String();
        $end = Carbon::now()->lastOfMonth()->toRfc3339String();

        Event::factory()->create(
            [
                'active_at' => $start,
                'expire_at' => $end,
                'group_name' => 'hg-testers',
                'event_name' => 'hg-test-event',
            ]
        );

        $knownEvents = Event::all();

        $start = (new Carbon($start))->subMonth()->toRfc3339String();
        $end = (new Carbon($end))->endOfMonth()->toRfc3339String();

        $response = $this->call('GET', '/api/calendar', compact('start', 'end'));

        $response->assertOk()
            ->assertJsonCount($knownEvents->count())
            ->assertJsonStructure(
                [
                    [
                        'start',
                        'end',
                        'title',
                        'description',
                        'allDay',
                        'start_fmt',
                        'end_fmt',
                        'location',
                        'event_id',
                        'event_url',
                    ],
                ]
            );

        expect($response->json('0.title'))->toBe("hg-testers\nhg-test-event");
    }
});
