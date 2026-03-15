<?php

namespace App\Providers;

use App\Events\UserCreated;
use App\Listeners\EmailNewUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('app.force_ssl')) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrap();

        Event::listen(UserCreated::class, EmailNewUser::class);
    }

    public function register(): void
    {
        Model::shouldBeStrict(App::environment(['local', 'testing']));

        if (config('telescope.enabled')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
