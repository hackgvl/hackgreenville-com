<?php

namespace HackGreenville\SlackEventsBot\Services;

use App\Models\Event;
use Carbon\Carbon;
use Exception;
use HackGreenville\SlackEventsBot\Exceptions\UnsafeMessageSpilloverException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BotService
{
    public function __construct(
        private DatabaseService       $databaseService,
        private MessageBuilderService $messageBuilderService,
    ) {
    }

    public function postOrUpdateMessages(Carbon $week, array $messages): void
    {
        $channels = $this->databaseService->getSlackChannelIds();
        $existingMessages = $this->databaseService->getMessages($week);

        $messageDetails = [];
        foreach ($channels as $channelId) {
            $messageDetails[$channelId] = [];
            $channelExistingMessages = $existingMessages->where('slack_channel_id', $channelId);

            foreach ($channelExistingMessages as $existingMessage) {
                $position = $existingMessage['sequence_position'];
                $messageDetails[$channelId][$position] = [
                    'timestamp' => $existingMessage['message_timestamp'],
                    'message_text' => $existingMessage['message'],
                ];
            }
        }

        foreach ($channels as $slackChannelId) {
            try {
                foreach ($messages as $msgIdx => $msg) {
                    $msgText = $msg['text'];
                    $msgBlocks = $msg['blocks'];

                    $existingMsgDetail = $messageDetails[$slackChannelId][$msgIdx] ?? null;

                    if (
                        ! $existingMsgDetail
                        || $msgText !== $existingMsgDetail['message_text']
                    ) {
                        if ( ! $existingMsgDetail) {
                            if ($this->isUnsafeToSpillover(
                                count($existingMessages->where('slack_channel_id', $slackChannelId)),
                                count($messages),
                                $week,
                                $slackChannelId
                            )) {
                                throw new UnsafeMessageSpilloverException;
                            }

                            Log::info("Posting new message " . ($msgIdx + 1) . " for week {$week->format('F j')} in {$slackChannelId}");

                            $slackResponse = $this->postNewMessage($slackChannelId, $msgBlocks, $msgText);

                            $this->databaseService->createMessage(
                                $week,
                                $msgText,
                                $slackResponse['ts'],
                                $slackChannelId,
                                $msgIdx
                            );
                        } else { // An existing message needs to be updated
                            if ($this->isUnsafeToSpillover(
                                count($existingMessages->where('slack_channel_id', $slackChannelId)),
                                count($messages),
                                $week,
                                $slackChannelId
                            )) {
                                throw new UnsafeMessageSpilloverException;
                            }

                            Log::info(
                                "Updating message " . ($msgIdx + 1) . " for week {$week->format('F j')} " .
                                "in {$slackChannelId} due to text content change."
                            );

                            $timestamp = $existingMsgDetail['timestamp'];

                            $slackResponse = Http::withToken(config('slack-events-bot.bot_token'))
                                ->post('https://slack.com/api/chat.update', [
                                    'ts' => $timestamp,
                                    'channel' => $slackChannelId,
                                    'blocks' => $msgBlocks,
                                    'text' => $msgText,
                                ]);

                            $json = $slackResponse->json();

                            if ( ! $slackResponse->successful() || ! ($json['ok'] ?? false)) {
                                $error = $json['error'] ?? 'unknown_error';
                                Log::error("Failed to update message {$timestamp} in Slack channel {$slackChannelId}", [
                                    'error' => $error,
                                    'response' => $json,
                                ]);
                                throw new Exception("Slack API error when updating message: {$error}");
                            }

                            $this->databaseService->updateMessage(
                                $week,
                                $msgText,
                                $timestamp,
                                $slackChannelId
                            );
                        }
                    } else {
                        Log::info(
                            "Message " . ($msgIdx + 1) . " for week of {$week->format('F j')} " .
                            "in {$slackChannelId} hasn't changed, not updating"
                        );
                    }
                }
                // Handle deletion of messages if the new set has fewer messages
                $currentMessageCountForChannel = count($messageDetails[$slackChannelId] ?? []);
                for ($i = count($messages); $i < $currentMessageCountForChannel; $i++) {
                    $timestampToDelete = $messageDetails[$slackChannelId][$i]['timestamp'] ?? null;
                    if ($timestampToDelete) {
                        Log::info("Deleting old message (sequence_position " . ($i) . ") for week of {$week->format('F j')} in {$slackChannelId}. No longer needed.");
                        Http::withToken(config('slack-events-bot.bot_token'))
                            ->post('https://slack.com/api/chat.delete', [
                                'channel' => $slackChannelId,
                                'ts'      => $timestampToDelete,
                            ]);
                        $this->databaseService->deleteMessage($slackChannelId, $timestampToDelete);
                    }
                }

            } catch (UnsafeMessageSpilloverException $e) {
                Log::error(
                    "Cannot update messages for {$week->format('m/d/Y')} for channel {$slackChannelId}. " .
                    "New events have caused the number of messages needed to increase, " .
                    "but the next week's post has already been sent. Cannot resize. " .
                    "Existing message count: " . count($existingMessages->where('slack_channel_id', $slackChannelId)) . " --- New message count: " . count($messages)
                );
                throw $e;
            }
        }
    }

    public function parseEventsForWeek(Collection $events, Carbon $weekStart): void
    {
        $eventBlocks = $this->messageBuilderService->buildEventBlocks($events);
        $chunkedMessages = $this->messageBuilderService->chunkMessages($eventBlocks, $weekStart);

        $this->postOrUpdateMessages($weekStart, $chunkedMessages);
    }

    public function handlePostingToSlack(): void
    {
        $weekStart = now()->copy()->startOfWeek();

        $events = Event::query()
            ->with(['venue.state', 'organization'])
            ->published()
            ->whereBetween('active_at', [
                $weekStart,
                $weekStart->copy()->endOfWeek(),
            ])
            ->oldest('active_at')
            ->get();

        if ($events->isEmpty()) {
            Log::info("No upcoming events found for the week of {$weekStart->format('F j')}. Cleaning up any existing messages for this week.");
            $this->deleteMessagesForWeek($weekStart);
        } else {
            $this->parseEventsForWeek($events, $weekStart);
        }
    }

    private function deleteMessagesForWeek(Carbon $week): void
    {
        $messagesToDelete = $this->databaseService->getMessages($week);

        if ($messagesToDelete->isEmpty()) {
            return;
        }

        foreach ($messagesToDelete as $message) {
            Log::info("Deleting stale message {$message['message_timestamp']} in channel {$message['slack_channel_id']}.");
            Http::withToken(config('slack-events-bot.bot_token'))
                ->post('https://slack.com/api/chat.delete', [
                    'channel' => $message['slack_channel_id'],
                    'ts'      => $message['message_timestamp'],
                ]);
            // We don't care if the deletion fails on Slack's end (e.g., message already deleted),
            // we still want to remove it from our database.
        }

        $this->databaseService->deleteMessagesForWeek($week);
    }

    private function isUnsafeToSpillover(
        int    $existingMessagesLength,
        int    $newMessagesLength,
        Carbon $week,
        string $slackChannelId
    ): bool {
        if ($newMessagesLength > $existingMessagesLength && $existingMessagesLength > 0) {
            $latestMessage = $this->databaseService->getMostRecentMessageForChannel($slackChannelId);

            if ( ! $latestMessage) {
                return false;
            }

            $latestMessageWeek = Carbon::parse($latestMessage['week']);

            // If the latest message is for a more recent week then it is unsafe
            // to add new messages. We cannot place new messages before older, existing ones.
            return $latestMessageWeek->greaterThan($week);
        }

        return false;
    }

    private function postNewMessage(string $slackChannelId, array $msgBlocks, string $msgText): array
    {
        $slackResponse = Http::withToken(config('slack-events-bot.bot_token'))
            ->post('https://slack.com/api/chat.postMessage', [
                'channel' => $slackChannelId,
                'blocks' => $msgBlocks,
                'text' => $msgText,
                'unfurl_links' => false,
                'unfurl_media' => false,
            ]);

        $json = $slackResponse->json();

        if ( ! $slackResponse->successful() || ! ($json['ok'] ?? false)) {
            $error = $json['error'] ?? 'unknown_error';
            Log::error("Failed to post new message to Slack channel {$slackChannelId}", [
                'error' => $error,
                'response' => $json,
            ]);
            throw new Exception("Slack API error when posting message: {$error}");
        }

        return $json;
    }
}
