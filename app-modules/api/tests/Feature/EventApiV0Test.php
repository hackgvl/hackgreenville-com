<?php

namespace HackGreenville\Api\Tests\Feature;

use App\Models\Event;
use Tests\DatabaseTestCase;

class EventApiV0Test extends DatabaseTestCase
{
    public function test_event_api_v0_return_correct_data(): void
    {
        // Lock-in the time
        $this->travelTo(now());

        $event = Event::factory()->create([
            'cancelled_at' => now(),
        ]);

        $this->getJson(route('api.v0.events.index'))
            ->assertSessionDoesntHaveErrors()
            ->assertExactJson([
                [
                    'event_name' => $event->event_name,
                    'group_name' => $event->group_name,
                    'group_url' => 'TBD', //FIXME
                    'url' => $event->url,
                    'time' => $event->active_at->toISOString(),
                    'tags' => '1', // TODO,
                    'nid' => '1', // TODO,
                    'rsvp_count' => $event->rsvp_count,
                    'created_at' => $event->created_at->toISOString(),
                    'description' => $event->description,
                    'uuid' => $event->event_uuid,
                    'data_as_of' => now()->toISOString(),
                    'status' => 'cancelled',
                    'service_id' => $event->service_id,
                    'service' => $event->service->value,
                    'venue' => [
                        'name' => $event->venue->name,
                        'address' => $event->venue->address,
                        'city' => $event->venue->city,
                        'state' => $event->venue->state->abbr,
                        'zip' => $event->venue->zipcode,
                        'country' => $event->venue->country,
                        'lat' => $event->venue->lat,
                        'lon' => $event->venue->lon,
                    ],
                ],
            ]);
    }
}
