<?php

namespace App\Console\Commands;

use DateInterval;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Symfony\Component\Console\Helper\ProgressBar;

class ClearOrgsAndEvents extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-orgs-and-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears out the events and orgs tables for debugging purposes.';

    /*
     * Lock timeout for the command.
     */
    public function isolationLockExpiresAt(): DateTimeInterface|DateInterval
    {
        return now()->addMinutes(1);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $models_to_reset = [
            "Events" => "App\Models\Event",
            "Orgs" => "App\Models\Org"
        ];

        ProgressBar::setFormatDefinition('simplified', '%current% of %max% tables wiped --- %message%');
        $progressIndicator = $this->output->createProgressBar(sizeof($models_to_reset));
        $progressIndicator->setFormat('simplified');

        $progressIndicator->start();

        foreach ($models_to_reset as $model_name => $model_namespace) {
            $progressIndicator->setMessage("Resetting " . $model_name);
            $model_namespace::truncate();
            $progressIndicator->advance();
        }

        $progressIndicator->setMessage("Events and Orgs have been successfully wiped.");
        $progressIndicator->finish();
    }
}
