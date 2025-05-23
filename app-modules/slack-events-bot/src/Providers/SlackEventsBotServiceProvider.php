<?php

namespace HackGreenville\SlackEventsBot\Providers;

use HackGreenville\SlackEventsBot\Console\Commands\CheckApiCommand;
use HackGreenville\SlackEventsBot\Console\Commands\DeleteOldMessagesCommand;
use HackGreenville\SlackEventsBot\Services\AuthService;
use HackGreenville\SlackEventsBot\Services\BotService;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use HackGreenville\SlackEventsBot\Services\EventService;
use HackGreenville\SlackEventsBot\Services\MessageBuilderService;
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

        // Register services
        $this->app->singleton(AuthService::class);
        $this->app->singleton(BotService::class);
        $this->app->singleton(DatabaseService::class);
        $this->app->singleton(EventService::class);
        $this->app->singleton(MessageBuilderService::class);
    }

    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/slack-events-bot.php' => config_path('slack-events-bot.php'),
        ], 'slack-events-bot-config');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckApiCommand::class,
                DeleteOldMessagesCommand::class,
            ]);
        }

        // Schedule tasks
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            // Check events every hour
            $schedule->command('slack:check-events')->hourly();

            // Delete old messages once daily
            $schedule->command('slack:delete-old-messages')->daily();
        });
    }
}
