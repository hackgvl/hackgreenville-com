<?php

namespace Tests\Feature\Models;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_ongoing_and_future_includes_ongoing_events_expiring_right_now(): void
    {
        $this->freezeTime();
        $event = Event::factory()->create(['expire_at' => now()]);

        $this->assertTrue(Event::future()->get()->contains($event));
    }

    public function test_scope_ongoing_and_future_includes_events_that_expired_earlier_today(): void
    {
        $this->freezeTime();
        $event = Event::factory()->create(['expire_at' => now()->startOfDay()]);

        $this->assertTrue(Event::future()->get()->contains($event));
    }

    public function test_scope_ongoing_and_future_includes_multi_day_events_expiring_in_the_future(): void
    {
        $this->freezeTime();
        $event = Event::factory()->create(['expire_at' => now()->addDays(3)]);

        $this->assertTrue(Event::future()->get()->contains($event));
    }

    public function test_scope_ongoing_and_future_excludes_events_that_expired_yesterday(): void
    {
        $this->freezeTime();
        $event = Event::factory()->create(['expire_at' => now()->subDay()->endOfDay()]);

        $this->assertFalse(Event::future()->get()->contains($event));
    }
}
