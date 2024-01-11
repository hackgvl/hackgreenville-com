<?php

namespace App\Console\Commands;

use DateInterval;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Symfony\Component\Console\Helper\ProgressBar;

class TruncateOrgsAndEvents extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:truncate-orgs-and-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncates the events and orgs tables for debugging purposes.';

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
        $models_to_truncate = [
            "Events" => "App\Models\Event",
            "Orgs" => "App\Models\Org"
        ];

        ProgressBar::setFormatDefinition('simplified', '%current% of %max% tables truncated --- %message%');
        $progressIndicator = $this->output->createProgressBar(sizeof($models_to_truncate));
        $progressIndicator->setFormat('simplified');

        $progressIndicator->start();

        foreach ($models_to_truncate as $model_name => $model_namespace) {
            $progressIndicator->setMessage("Truncating " . $model_name);
            $model_namespace::truncate();
            $progressIndicator->advance();
        }

        $progressIndicator->setMessage("Events and Orgs have been successfully truncated.");
        $progressIndicator->finish();
    }
}
