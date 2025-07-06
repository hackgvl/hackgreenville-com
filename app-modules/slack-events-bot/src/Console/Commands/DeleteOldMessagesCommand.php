<?php

namespace HackGreenville\SlackEventsBot\Console\Commands;

use Exception;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use Illuminate\Console\Command;

class DeleteOldMessagesCommand extends Command
{
    protected $signature = 'slack:delete-old-messages {--days=90 : Days to keep messages}';
    protected $description = 'Delete old Slack messages and cooldowns';

    public function __construct(private DatabaseService $databaseService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $days = (int) $this->option('days');
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
