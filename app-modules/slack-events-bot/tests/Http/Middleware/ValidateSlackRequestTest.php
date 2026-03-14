<?php

namespace HackGreenville\SlackEventsBot\Tests\Http\Middleware;

use HackGreenville\SlackEventsBot\Http\Middleware\ValidateSlackRequest;
use HackGreenville\SlackEventsBot\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ValidateSlackRequestTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_returns_401_when_validation_fails(): void
    {
        $authService = Mockery::mock(AuthService::class);
        $authService->shouldReceive('validateSlackRequest')->once()->andReturn(false);

        $middleware = new ValidateSlackRequest($authService);
        $request = Request::create('/slack/events', 'POST');

        $response = $middleware->handle($request, fn () => new Response('OK', 200));

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Unauthorized', $response->getContent());
    }

    #[Test]
    public function it_passes_request_through_when_validation_succeeds(): void
    {
        $authService = Mockery::mock(AuthService::class);
        $authService->shouldReceive('validateSlackRequest')->once()->andReturn(true);

        $middleware = new ValidateSlackRequest($authService);
        $request = Request::create('/slack/events', 'POST');

        $response = $middleware->handle($request, fn () => new Response('OK', 200));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }
}
