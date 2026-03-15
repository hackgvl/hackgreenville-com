<?php

namespace HackGreenville\SlackEventsBot\Services;

use HackGreenville\SlackEventsBot\Exceptions\SlackApiException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SlackApiClient
{
    public function postMessage(string $token, string $channelId, array $blocks, string $text): array
    {
        $response = $this->postWithRetry($token, 'chat.postMessage', [
            'channel' => $channelId,
            'blocks' => $blocks,
            'text' => $text,
            'unfurl_links' => false,
            'unfurl_media' => false,
        ]);

        return $this->validateResponse($response, "posting message to channel {$channelId}");
    }

    public function updateMessage(string $token, string $channelId, string $timestamp, array $blocks, string $text): array
    {
        $response = $this->postWithRetry($token, 'chat.update', [
            'ts' => $timestamp,
            'channel' => $channelId,
            'blocks' => $blocks,
            'text' => $text,
        ]);

        return $this->validateResponse($response, "updating message {$timestamp} in channel {$channelId}");
    }

    public function deleteMessage(string $token, string $channelId, string $timestamp): array
    {
        $response = $this->postWithRetry($token, 'chat.delete', [
            'channel' => $channelId,
            'ts' => $timestamp,
        ]);

        return $response->json();
    }

    public function getUserInfo(string $token, string $userId): array
    {
        $response = Http::withToken($token)
            ->get('https://slack.com/api/users.info', [
                'user' => $userId,
            ]);

        return $this->validateResponse($response, 'fetching user info');
    }

    private function postWithRetry(string $token, string $method, array $data): Response
    {
        return Http::withToken($token)
            ->retry(3, fn (int $attempt, $exception) => $this->retryDelay($attempt, $exception))
            ->post("https://slack.com/api/{$method}", $data);
    }

    private function validateResponse(Response $response, string $context): array
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
