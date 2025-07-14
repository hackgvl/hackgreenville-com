<?php

namespace HackGreenville\SlackEventsBot\Tests\Console\Commands;

use Exception;
use HackGreenville\SlackEventsBot\Services\DatabaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class DeleteOldMessagesCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_deletes_old_messages_with_default_days_option(): void
    {
        $this->mock(DatabaseService::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteOldMessages')->once()->with(90);
        });

        $this->artisan('slack:delete-old-messages')
            ->expectsOutput('Deleting messages and cooldowns older than 90 days...')
            ->expectsOutput('Old messages and cooldowns deleted successfully!')
            ->assertSuccessful();
    }

    /** @test */
    public function it_deletes_old_messages_with_custom_days_option(): void
    {
        $this->mock(DatabaseService::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteOldMessages')->once()->with(30);
        });

        $this->artisan('slack:delete-old-messages', ['--days' => 30])
            ->expectsOutput('Deleting messages and cooldowns older than 30 days...')
            ->expectsOutput('Old messages and cooldowns deleted successfully!')
            ->assertSuccessful();
    }

    /** @test */
    public function it_handles_exceptions_gracefully(): void
    {
        $this->mock(DatabaseService::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteOldMessages')->once()->with(90)->andThrow(new Exception('Test exception'));
        });

        $this->artisan('slack:delete-old-messages')
            ->expectsOutput('Deleting messages and cooldowns older than 90 days...')
            ->expectsOutput('Error deleting old messages and cooldowns: Test exception')
            ->assertFailed();
    }
}
