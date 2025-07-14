<?php

namespace HackGreenville\SlackEventsBot\Jobs;

use HackGreenville\SlackEventsBot\Services\BotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckEventsApi implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(BotService $botService): void
    {
        Log::info('Executing CheckEventsApi job.');
        try {
            $botService->handlePostingToSlack();
            Log::info('Finished CheckEventsApi job.');
        } catch (Throwable $e) {
            Log::error('CheckEventsApi job failed with an exception.', [
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
