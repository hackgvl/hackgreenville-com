<?php

namespace App\Providers;

use App\Contracts\CalendarContract;
use App\Http\Clients\GoogleCalendar;
use Illuminate\Support\ServiceProvider;

class CalendarProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CalendarContract::class, function () {
            $calendar = new GoogleCalendar();

            return $calendar->initialize();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
