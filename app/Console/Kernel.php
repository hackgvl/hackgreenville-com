<?php

namespace App\Console;

use HackGreenville\EventImporter\Console\Commands\ImportEventsCommand;
use HackGreenville\EventImporter\Console\Commands\PruneMissingEventsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ImportEventsCommand::class)->hourly();

        $schedule->command(PruneMissingEventsCommand::class)->dailyAt('02:00');

        if(config('telescope.enabled')) {
            $schedule->command('telescope:prune')->daily();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
