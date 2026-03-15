<?php

namespace HackGreenville\SlackEventsBot\Services\Slack;

class ChatClient extends BaseClient
{
    public function postMessage(string $channelId, array $blocks, string $text): array
    {
        $response = $this->postWithRetry('chat.postMessage', [
            'channel' => $channelId,
            'blocks' => $blocks,
            'text' => $text,
            'unfurl_links' => false,
            'unfurl_media' => false,
        ]);

        return $this->validateResponse($response, "posting message to channel {$channelId}");
    }

    public function update(string $channelId, string $timestamp, array $blocks, string $text): array
    {
        $response = $this->postWithRetry('chat.update', [
            'ts' => $timestamp,
            'channel' => $channelId,
            'blocks' => $blocks,
            'text' => $text,
        ]);

        return $this->validateResponse($response, "updating message {$timestamp} in channel {$channelId}");
    }

    public function delete(string $channelId, string $timestamp): array
    {
        $response = $this->postWithRetry('chat.delete', [
            'channel' => $channelId,
            'ts' => $timestamp,
        ]);

        return $response->json();
    }
}
