<?php

namespace HackGreenville\SlackEventsBot\Tests\Jobs;

use Exception;
use HackGreenville\SlackEventsBot\Jobs\CheckEventsApi;
use HackGreenville\SlackEventsBot\Services\BotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;
use Throwable;

class CheckEventsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mockery::close(); // Ensure mocks are clean before each test
    }

    /**
     * Test that the job calls BotService::handlePostingToSlack() and logs success.
     */
    public function test_job_executes_successfully()
    {
        // Mock the BotService
        $botServiceMock = Mockery::mock(BotService::class);
        $botServiceMock->shouldReceive('handlePostingToSlack')
            ->once()
            ->andReturnNull(); // Simulate successful execution

        // Bind the mock to the service container
        $this->app->instance(BotService::class, $botServiceMock);

        // Expect specific log messages
        Log::shouldReceive('info')
            ->once()
            ->with('Executing CheckEventsApi job.');

        Log::shouldReceive('info')
            ->once()
            ->with('Finished CheckEventsApi job.');

        // Dispatch the job
        CheckEventsApi::dispatchSync();
    }

    /**
     * Test that the job handles exceptions from BotService::handlePostingToSlack()
     * by logging the error and re-throwing the exception.
     */
    public function test_job_handles_exceptions()
    {
        // Create a dummy exception
        $exception = new Exception('Simulated BotService error');

        // Mock the BotService to throw an exception
        $botServiceMock = Mockery::mock(BotService::class);
        $botServiceMock->shouldReceive('handlePostingToSlack')
            ->once()
            ->andThrow($exception);

        // Bind the mock to the service container
        $this->app->instance(BotService::class, $botServiceMock);

        // Expect specific log messages
        Log::shouldReceive('info')
            ->once()
            ->with('Executing CheckEventsApi job.');

        Log::shouldReceive('error')
            ->once()
            ->withArgs(fn ($message, $context) => str_contains($message, 'CheckEventsApi job failed with an exception.') &&
                       $context['exception'] === get_class($exception) &&
                       $context['message'] === $exception->getMessage() &&
                       $context['file'] === $exception->getFile() &&
                       $context['line'] === $exception->getLine());

        // Expect the exception to be re-thrown
        $this->expectException(Throwable::class);
        $this->expectExceptionMessage('Simulated BotService error');

        // Dispatch the job
        CheckEventsApi::dispatchSync();
    }
}
