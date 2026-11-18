<?php

namespace App\Providers;

use App\Events\UserCreated;
use App\Listeners\EmailNewUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Knuckles\Scribe\Scribe;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('app.force_ssl')) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrap();

        Event::listen(UserCreated::class, EmailNewUser::class);

        $this->registerQueryMacros();

        Scribe::afterGenerating(function (array $paths) {

            // Scribe renders Blade in base_url for code samples but not for the introductory paragraph,
            // so the escaped `@{{ ... }}` from config/scribe.php survives there. Unescape it
            // so the Base URL resolves per environment like the rest of the page.

            if ($view = $paths['blade'] ?? null) {
                file_put_contents($view, str_replace(
                    config("scribe.base_url"),
                    '{{ config("app.url") }}',
                    file_get_contents($view),
                ));
            }
        });
    }

    public function register(): void
    {
        Model::shouldBeStrict(App::environment(['local', 'testing']));

        if (config('telescope.enabled')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Register reusable Eloquent query macros.
     */
    private function registerQueryMacros(): void
    {
        /**
         * Case-insensitive "contains" filter that safely escapes the LIKE
         * wildcards (`%`, `_`) and the escape character (`!`) in user input,
         * so a search term like "50%" matches literally instead of acting as
         * a wildcard. Shared by the public API controllers.
         *
         * @param  string  $column  A trusted column identifier (never user input).
         */
        Builder::macro('whereLikeContains', function (string $column, ?string $value): Builder {
            $escaped = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], (string) $value);

            /** @var Builder $this */
            return $this->whereRaw("{$column} LIKE ? ESCAPE '!'", ['%' . $escaped . '%']);
        });
    }
}
