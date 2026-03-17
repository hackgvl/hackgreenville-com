<?php

namespace HackGreenville\SlackEventsBot\Services\Slack;

class ChatClient extends BaseClient
{
    public function __construct(string $token, private string $channelId)
    {
        parent::__construct($token);
    }

    public function postMessage(array $blocks, string $text): array
    {
        $response = $this->postWithRetry('chat.postMessage', [
            'channel' => $this->channelId,
            'blocks' => $blocks,
            'text' => $text,
            'unfurl_links' => false,
            'unfurl_media' => false,
        ]);

        return $this->validateResponse($response, "posting message to channel {$this->channelId}");
    }

    public function update(string $timestamp, array $blocks, string $text): array
    {
        $response = $this->postWithRetry('chat.update', [
            'ts' => $timestamp,
            'channel' => $this->channelId,
            'blocks' => $blocks,
            'text' => $text,
        ]);

        return $this->validateResponse($response, "updating message {$timestamp} in channel {$this->channelId}");
    }

    /**
     * Delete a message. Returns raw JSON without validation because the caller
     * needs to inspect the error — 'message_not_found' is treated as success.
     *
     * @see \HackGreenville\SlackEventsBot\Services\BotService::deleteSlackMessage()
     */
    public function delete(string $timestamp): array
    {
        $response = $this->postWithRetry('chat.delete', [
            'channel' => $this->channelId,
            'ts' => $timestamp,
        ]);

        return $response->json();
    }
}
