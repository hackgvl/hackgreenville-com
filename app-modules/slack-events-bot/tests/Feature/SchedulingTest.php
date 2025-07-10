<?php

namespace HackGreenville\SlackEventsBot\Tests\Feature;

use Illuminate\Console\Scheduling\Schedule;
use Tests\TestCase;

class SchedulingTest extends TestCase
{
    /** @test */
    public function it_schedules_the_delete_old_messages_command_daily()
    {
        $schedule = $this->app->make(Schedule::class);

        $event = collect($schedule->events())->first(fn ($event) => property_exists($event, 'command') && str_contains($event->command, 'slack:delete-old-messages'));

        $this->assertNotNull($event, 'The delete old messages command is not scheduled.');
        $this->assertEquals('0 0 * * *', $event->expression);
    }

    /** @test */
    public function it_schedules_the_check_events_api_job_hourly()
    {
        $schedule = $this->app->make(Schedule::class);

        $event = collect($schedule->events())->first(fn ($event) => $event instanceof \Illuminate\Console\Scheduling\CallbackEvent &&
                   str_contains($event->description, 'CheckEventsApi'));

        $this->assertNotNull($event, 'The check events api job is not scheduled.');
        $this->assertEquals('0 * * * *', $event->expression);
    }
}
