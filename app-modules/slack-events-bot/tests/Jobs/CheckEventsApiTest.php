<?php

namespace HackGreenville\SlackEventsBot\Tests\Jobs;

use Exception;
use HackGreenville\SlackEventsBot\Jobs\CheckEventsApi;
use HackGreenville\SlackEventsBot\Services\BotService;
use Mockery;
use Tests\DatabaseTestCase;
use Throwable;

class CheckEventsApiTest extends DatabaseTestCase
{
    public function test_job_executes_successfully()
    {
        $botServiceMock = Mockery::mock(BotService::class);
        $botServiceMock->shouldReceive('handlePostingToSlack')
            ->once()
            ->andReturnNull();

        $this->app->instance(BotService::class, $botServiceMock);

        CheckEventsApi::dispatchSync();
    }

    public function test_job_has_retry_configuration()
    {
        $job = new CheckEventsApi;

        $this->assertEquals(3, $job->tries);
        $this->assertEquals(2, $job->maxExceptions);
        $this->assertEquals([30, 120, 300], $job->backoff);
    }

    public function test_job_handles_exceptions()
    {
        $exception = new Exception('Simulated BotService error');

        $botServiceMock = Mockery::mock(BotService::class);
        $botServiceMock->shouldReceive('handlePostingToSlack')
            ->once()
            ->andThrow($exception);

        $this->app->instance(BotService::class, $botServiceMock);

        $this->expectException(Throwable::class);
        $this->expectExceptionMessage('Simulated BotService error');

        CheckEventsApi::dispatchSync();
    }
}
