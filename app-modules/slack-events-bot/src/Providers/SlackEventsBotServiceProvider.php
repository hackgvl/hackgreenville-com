<?php

namespace HackGreenville\SlackEventsBot\Providers;

use HackGreenville\SlackEventsBot\Console\Commands\DeleteOldMessagesCommand;
use HackGreenville\SlackEventsBot\Services\AuthService;
use HackGreenville\SlackEventsBot\Services\BotService;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use HackGreenville\SlackEventsBot\Services\EventService;
use HackGreenville\SlackEventsBot\Services\MessageBuilderService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SlackEventsBotServiceProvider extends ServiceProvider
{
    /**
     * The module's root directory.
     *
     * @var string
     */
    protected string $moduleDir = __DIR__ . '/../..';
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
        $this->loadRoutes();

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeleteOldMessagesCommand::class,
            ]);
        }

        // Schedule tasks
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            // Delete old messages once daily
            $schedule->command('slack:delete-old-messages')->daily();
        });
    }

    protected function loadRoutes(): void
    {
        Route::prefix('slack')
            ->middleware('api')
            ->name('slack.')
            ->group("{$this->moduleDir}/routes/api.php");

        Route::middleware('web')
            ->group("{$this->moduleDir}/routes/web.php");
    }
}
