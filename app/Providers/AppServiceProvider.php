<?php

namespace App\Providers;

use App\Contracts\CalendarContract;
use App\Http\Clients\GoogleCalendar;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        JsonResource::withoutWrapping();

        $this->app->singleton(
                CalendarContract::class,
                function () {
                    return new GoogleCalendar();
                }
        );
    }
}
