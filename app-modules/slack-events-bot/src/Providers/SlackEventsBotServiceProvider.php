<?php

namespace HackGreenville\SlackEventsBot\Providers;

use HackGreenville\SlackEventsBot\Console\Commands\DeleteOldMessagesCommand;
use HackGreenville\SlackEventsBot\Jobs\CheckEventsApi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class SlackEventsBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/slack-events-bot.php',
            'slack-events-bot'
        );
    }

    public function boot(): void
    {
        // Register migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Register views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'slack-events-bot');

        // Register routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeleteOldMessagesCommand::class,
            ]);
        }

        // Schedule tasks
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('slack:delete-old-messages')->daily();
            $schedule->job(new CheckEventsApi)->hourly();
        });
    }
}
