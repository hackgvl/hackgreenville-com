<?php

use App\Console\Commands\PullEventsCommand;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('marks event as canceled if not in the API response', function () {
    Carbon::setTestNow('2023-06-30 16:00:00');

    // Create an event in the database
    $event = Event::factory()->create([
        'cancelled_at' => null,  // Assuming events have a 'status' field
        'active_at' => now()->addDays(1),
        'expire_at' => now()->addDays(2),
    ]);

    // Mock the API response to not include the event
    Http::fake([
        config('app.events_api_domain') => Http::response([
            [
                'event_name' => 'Weekly Friday Afternoon Garden Tending',
                'group_name' => 'SynergyMill Community Workshop',
                'group_url' => 'https://synergymill.com',
                'venue' => [
                    'name' => 'Synergy Mill',
                    'address' => '400 Birnie St Ext',
                    'city' => 'Greenville',
                    'state' => 'SC',
                    'zip' => '',
                    'country' => 'us',
                    'lat' => 34.849491119385,
                    'lon' => -82.414642333984,
                ],
                'url' => 'https://www.meetup.com/synergymill/events/kqzwctyfcjbnc/',
                'time' => '2023-06-30T20:00:00Z',
                'tags' => 1,
                'rsvp_count' => 1,
                'created_at' => '2023-03-22T15:08:22Z',
                'description' => 'We\'ll be tending the garden...',
                'uuid' => 'cad24922-f53c-42ee-a7d0-eb15f74b7d86',
                'nid' => 25,
                'data_as_of' => '2023-07-01T14:40:08Z',
                'status' => 'past',
                'service_id' => 'kqzwctyfcjbnc',
                'service' => 'meetup',
                'localtime' => Carbon::parse('2023-06-30 16:00:00'),
            ]
        ], 200),  // Empty response
    ]);

    // Run the command to cancel events not in the API response
    Artisan::call(PullEventsCommand::class);

    // Re-retrieve the event from the database
    $event = Event::find($event->id);
    $cancelled_at = $event->cancelled_at;

    // Assert that the event status is 'canceled'
    expect($event->cancelled_at)->not()->toBeNull('Event is not showing canceled and it should be');
});
