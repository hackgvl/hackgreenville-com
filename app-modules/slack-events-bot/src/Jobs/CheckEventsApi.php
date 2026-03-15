<?php

namespace HackGreenville\SlackEventsBot\Jobs;

use HackGreenville\SlackEventsBot\Services\BotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckEventsApi implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $maxExceptions = 2;
    public array $backoff = [30, 120, 300];

    /**
     * Execute the job.
     */
    public function handle(BotService $botService): void
    {
        $botService->handlePostingToSlack();
    }
}
