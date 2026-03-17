<?php

namespace HackGreenville\SlackEventsBot\Console\Commands;

use Exception;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use Illuminate\Console\Command;

class DeleteOldMessagesCommand extends Command
{
    protected $signature = 'slack:delete-old-messages {--days= : Days to keep messages (default: from config)}';
    protected $description = 'Delete old Slack messages and cooldowns';

    public function __construct(private DatabaseService $databaseService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?? config('slack-events-bot.old_messages_retention_days'));

        if ($days < 1) {
            $this->error('Days must be at least 1.');

            return self::FAILURE;
        }

        $this->info("Deleting messages and cooldowns older than {$days} days...");

        try {
            $this->databaseService->deleteOldMessages($days);
            $this->info('Old messages and cooldowns deleted successfully!');
            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error('Error deleting old messages and cooldowns: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
