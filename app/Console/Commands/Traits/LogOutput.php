<?php

namespace App\Console\Commands\Traits;

use Illuminate\Support\Facades\Log;

trait LogOutput
{
    public function logError(string $message): void
    {
        $this->error($message);

        Log::error("{$message} " . __CLASS__);
    }

    public function logInfo(string $message): void
    {
        $this->info($message);

        Log::info("{$message} " . __CLASS__);
    }
}
