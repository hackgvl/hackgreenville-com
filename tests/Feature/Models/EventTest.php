<?php

namespace Tests\Feature\Models;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_ongoing_and_future_includes_ongoing_events_expiring_right_now(): void
    {
        $this->freezeTime();
        $event = Event::factory()->create(['expire_at' => now()]);

        $this->assertTrue(Event::ongoingAndFuture()->get()->contains($event));
    }

    public function test_scope_ongoing_and_future_includes_events_that_expired_earlier_today(): void
    {
        $this->freezeTime();
        $event = Event::factory()->create(['expire_at' => now()->startOfDay()]);

        $this->assertTrue(Event::ongoingAndFuture()->get()->contains($event));
    }

    public function test_scope_ongoing_and_future_includes_multi_day_events_expiring_in_the_future(): void
    {
        $this->freezeTime();
        $event = Event::factory()->create(['expire_at' => now()->addDays(3)]);

        $this->assertTrue(Event::ongoingAndFuture()->get()->contains($event));
    }

    public function test_scope_ongoing_and_future_excludes_events_that_expired_yesterday(): void
    {
        $this->freezeTime();
        $event = Event::factory()->create(['expire_at' => now()->subDay()->endOfDay()]);

        $this->assertFalse(Event::ongoingAndFuture()->get()->contains($event));
    }

    public function test_scope_ongoing_between_includes_events_fully_within_the_window(): void
    {
        [$start, $end] = $this->januaryWindow();
        $event = Event::factory()->create([
            'active_at' => '2020-01-10 18:00:00',
            'expire_at' => '2020-01-10 21:00:00',
        ]);

        $this->assertTrue(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    public function test_scope_ongoing_between_includes_multi_day_events_that_start_before_the_window(): void
    {
        [$start, $end] = $this->januaryWindow();
        // A conference that began in December but is still running in January
        $event = Event::factory()->create([
            'active_at' => '2019-12-28 09:00:00',
            'expire_at' => '2020-01-02 17:00:00',
        ]);

        $this->assertTrue(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    public function test_scope_ongoing_between_includes_multi_day_events_that_end_after_the_window(): void
    {
        [$start, $end] = $this->januaryWindow();
        // Starts in January but runs into February
        $event = Event::factory()->create([
            'active_at' => '2020-01-30 09:00:00',
            'expire_at' => '2020-02-03 17:00:00',
        ]);

        $this->assertTrue(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    public function test_scope_ongoing_between_includes_events_that_span_the_entire_window(): void
    {
        [$start, $end] = $this->januaryWindow();
        $event = Event::factory()->create([
            'active_at' => '2019-12-01 00:00:00',
            'expire_at' => '2020-03-01 00:00:00',
        ]);

        $this->assertTrue(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    public function test_scope_ongoing_between_excludes_events_that_ended_before_the_window(): void
    {
        [$start, $end] = $this->januaryWindow();
        $event = Event::factory()->create([
            'active_at' => '2019-12-10 18:00:00',
            'expire_at' => '2019-12-20 21:00:00',
        ]);

        $this->assertFalse(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    public function test_scope_ongoing_between_excludes_events_that_start_after_the_window(): void
    {
        [$start, $end] = $this->januaryWindow();
        $event = Event::factory()->create([
            'active_at' => '2020-02-05 18:00:00',
            'expire_at' => '2020-02-05 21:00:00',
        ]);

        $this->assertFalse(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    public function test_scope_ongoing_between_includes_events_with_no_expire_at_starting_within_the_window(): void
    {
        [$start, $end] = $this->januaryWindow();
        // With no expire_at the event is treated as ending at active_at
        $event = Event::factory()->create([
            'active_at' => '2020-01-15 18:00:00',
            'expire_at' => null,
        ]);

        $this->assertTrue(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    public function test_scope_ongoing_between_excludes_events_with_no_expire_at_starting_before_the_window(): void
    {
        [$start, $end] = $this->januaryWindow();
        // Falls back to active_at as the end, which is before the window
        $event = Event::factory()->create([
            'active_at' => '2019-12-20 18:00:00',
            'expire_at' => null,
        ]);

        $this->assertFalse(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    public function test_scope_ongoing_between_includes_events_whose_expire_at_equals_the_window_start(): void
    {
        [$start, $end] = $this->januaryWindow();
        // Boundary: expire_at exactly at the window start still counts (>=)
        $event = Event::factory()->create([
            'active_at' => '2019-12-20 09:00:00',
            'expire_at' => $start,
        ]);

        $this->assertTrue(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    public function test_scope_ongoing_between_includes_events_whose_active_at_equals_the_window_end(): void
    {
        [$start, $end] = $this->januaryWindow();
        // Boundary: an event beginning at the last instant of the window counts (<=)
        $event = Event::factory()->create([
            'active_at' => $end,
            'expire_at' => $end,
        ]);

        $this->assertTrue(Event::ongoingBetween($start, $end)->get()->contains($event));
    }

    /**
     * A fixed [start, end] window covering January 2020 that events are
     * tested against. The scope takes an explicit window, so unlike the
     * ongoingAndFuture tests above these don't depend on the current time.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function januaryWindow(): array
    {
        return [
            Carbon::parse('2020-01-01 00:00:00'),
            Carbon::parse('2020-01-31 23:59:59'),
        ];
    }
}
