<?php

namespace App\Console\Commands;

use BladeUI\Icons\Console\CacheCommand;
use Filament\Support\Commands\AssetsCommand;
use Illuminate\Console\Command;
use Psy\Command\ExitCommand;
use Sebdesign\ArtisanCloudflare\Commands\Cache\Purge;

class DeployRailwayCommand extends Command
{
    protected $signature = 'deploy:railway';

    public function handle(): int
    {
        $this->call(CacheCommand::class);
        $this->call(AssetsCommand::class);
        $this->call(CacheCommand::class);

        rescue(fn () => $this->call(CacheGitHubContributorsCommand::class), report: false);

        $this->call(Purge::class);

        return ExitCommand::SUCCESS;
    }
}
