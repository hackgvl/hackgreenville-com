<?php

namespace HackGreenville\SlackEventsBot\Http\Middleware;

use Closure;
use HackGreenville\SlackEventsBot\Services\AuthService;
use Illuminate\Http\Request;

class ValidateSlackRequest
{
    public function __construct(private AuthService $authService)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$this->authService->validateSlackRequest($request)) {
            return response('Unauthorized', 401);
        }

        return $next($request);
    }
}
