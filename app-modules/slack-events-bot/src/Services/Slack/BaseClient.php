<?php

namespace HackGreenville\SlackEventsBot\Services\Slack;

use HackGreenville\SlackEventsBot\Exceptions\SlackApiException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseClient
{
    public function __construct(protected string $token)
    {
    }

    protected function postWithRetry(string $method, array $data): Response
    {
        return Http::withToken($this->token)
            ->retry(3, fn (int $attempt, $exception) => $this->retryDelay($attempt, $exception))
            ->post("https://slack.com/api/{$method}", $data);
    }

    protected function validateResponse(Response $response, string $context): array
    {
        $json = $response->json();

        if ( ! $response->successful() || ! ($json['ok'] ?? false)) {
            $error = $json['error'] ?? 'unknown_error';
            Log::error("Slack API error when {$context}", [
                'error' => $error,
                'response' => $json,
            ]);
            throw new SlackApiException("Slack API error when {$context}: {$error}");
        }

        return $json;
    }

    /**
     * Calculate retry delay in milliseconds. Respects Slack's Retry-After
     * header (returned in seconds) when present, otherwise backs off linearly.
     */
    private function retryDelay(int $attempt, ?Throwable $exception): int
    {
        if ($exception instanceof RequestException) {
            $retryAfter = $exception->response?->header('Retry-After');
            if ($retryAfter && is_numeric($retryAfter)) {
                return (int) $retryAfter * 1000;
            }
        }

        return $attempt * 1000;
    }
}
