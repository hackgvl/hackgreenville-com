<?php

namespace HackGreenville\SlackEventsBot\Console\Commands;

use HackGreenville\SlackEventsBot\Services\BotService;
use Illuminate\Console\Command;

class CheckApiCommand extends Command
{
    protected $signature = 'slack:check-events';
    protected $description = 'Check for events and update Slack messages';

    public function __construct(private BotService $botService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Checking for events...');

        try {
            $this->botService->checkApi(); // Method name kept for backward compatibility
            $this->info('Event check completed successfully!');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error checking events: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
