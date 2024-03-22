<?php

namespace App\Console\Commands\Traits;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

trait LogOutput
{
    public function logError(string $message): void
    {
        if ($this instanceof Command) {
            $this->error($message);
        }

        Log::error("{$message} " . __CLASS__);
    }

    public function logInfo(string $message): void
    {
        if ($this instanceof Command) {
            $this->info($message);
        }

        Log::info($message);
    }
}
