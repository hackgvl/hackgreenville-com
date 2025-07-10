<?php

namespace HackGreenville\Api\Tests\Feature;

use App\Models\Event;
use App\Models\Org;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventApiV1Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_events_list()
    {
        $org = Org::factory()->create();
        $venue = Venue::factory()->create();

        $event = Event::factory()->create([
            'organization_id' => $org->id,
            'venue_id' => $venue->id,
            'active_at' => now(),
            'expire_at' => now()->addDays(1),
        ]);

        $response = $this->getJson('/api/v1/events');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'url',
                    'starts_at',
                    'ends_at',
                    'rsvp_count',
                    'status',
                    'organization',
                    'venue',
                    'service',
                    'created_at',
                    'updated_at',
                ]
            ],
            'links',
            'meta',
        ]);

        $response->assertJsonPath('data.0.id', $event->event_uuid);
        $response->assertJsonPath('data.0.starts_at', $event->active_at->toISOString());
        $response->assertJsonPath('data.0.ends_at', $event->expire_at->toISOString());
    }

    public function test_can_filter_events_by_date_range()
    {
        $org = Org::factory()->create();
        $venue = Venue::factory()->create();

        $pastEvent = Event::factory()->create([
            'organization_id' => $org->id,
            'venue_id' => $venue->id,
            'active_at' => now()->subDays(10),
            'expire_at' => now()->subDays(9),
        ]);

        $futureEvent = Event::factory()->create([
            'organization_id' => $org->id,
            'venue_id' => $venue->id,
            'active_at' => now()->addDays(5),
            'expire_at' => now()->addDays(6),
        ]);

        $response = $this->getJson('/api/v1/events?start_date=' . now()->addDays(1)->format('Y-m-d'));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $futureEvent->event_uuid);
    }

    public function test_can_paginate_events()
    {
        $org = Org::factory()->create();
        $venue = Venue::factory()->create();

        Event::factory()->count(20)->create([
            'organization_id' => $org->id,
            'venue_id' => $venue->id,
            'active_at' => now(),
            'expire_at' => now()->addDays(1),
        ]);

        $response = $this->getJson('/api/v1/events?per_page=10');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonPath('meta.per_page', 10);
        $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonPath('meta.last_page', 2);
    }
    
    public function test_can_filter_is_paid_events() {
        $org = Org::factory()->create();
        $venue = Venue::factory()->create();
        
        $free_event = Event::factory()->create([
            'organization_id' => $org->id,
            'venue_id' => $venue->id,
            'active_at' => now(),
            'expire_at' => now()->addDays(1),
            'is_paid' => false,
            'event_name' => 'Free Event',
        ]);

        $paid_event = Event::factory()->create([
            'organization_id' => $org->id,
            'venue_id' => $venue->id,
            'active_at' => now(),
            'expire_at' => now()->addDays(1),
            'is_paid' => true,
            'event_name' => 'Paid Event',
        ]);

        $unknown_event = Event::factory()->create([
            'organization_id' => $org->id,
            'venue_id' => $venue->id,
            'active_at' => now(),
            'expire_at' => now()->addDays(1),
            'is_paid' => null,
            'event_name' => 'Unknown Event',
        ]);

        $response1 = $this->getJson('/api/v1/events');
        $response2 = $this->getJson('/api/v1/events?is_paid=true');
        $response3 = $this->getJson('/api/v1/events?is_paid=false');
        $response4 = $this->getJson('/api/v1/events?is_paid=null');

        $response1->assertStatus(200);
        $response2->assertStatus(200);
        $response3->assertStatus(200);
        $response4->assertStatus(200);

        $response1->assertJsonCount(3, 'data');

        $response2->assertJsonCount(1, 'data');
        $response2->assertSee($paid_event->event_name);

        $response3->assertJsonCount(1, 'data');
        $response3->assertSee($free_event->event_name);

        $response4->assertJsonCount(1, 'data');
        $response4->assertSee($unknown_event->event_name);
    }
}
