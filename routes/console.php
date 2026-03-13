<?php

use HackGreenville\EventImporter\Console\Commands\ImportEventsCommand;
use HackGreenville\EventImporter\Console\Commands\PruneMissingEventsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ImportEventsCommand::class)->hourly();
Schedule::command(PruneMissingEventsCommand::class)->dailyAt('02:00');

if (config('telescope.enabled')) {
    Schedule::command('telescope:prune')->daily();
}
