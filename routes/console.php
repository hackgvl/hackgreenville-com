<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('events:back-fill-uuid', function () {
    $updated_count = \App\Models\Event::whereNull('event_uuid')->orWhere('event_uuid', '')
                                      ->get()
                                      ->each(function ($event) {
                                          $event->update(['event_uuid' => $event->cache[ 'uuid' ]]);
                                          return $event;
                                      })->count();

    print sprintf("Updated %s %s.\r\n", $updated_count, str_plural('event', $updated_count));
})->describe('Looks for events missing its event_uuid and fills it from the event cache');
