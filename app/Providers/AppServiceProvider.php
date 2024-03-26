<?php

namespace App\Providers;

use App\Contracts\CalendarContract;
use App\Http\Clients\GoogleCalendar;
use App\Http\Clients\UpstateClient;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            CalendarContract::class,
            fn () => new GoogleCalendar
        );

        if(config('telescope.enabled')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->singleton('UpstateClient', fn () => new UpstateClient);
    }
}
