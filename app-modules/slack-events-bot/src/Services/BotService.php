<?php

namespace HackGreenville\SlackEventsBot\Services;

use App\Models\Event;
use Carbon\Carbon;
use Exception;
use HackGreenville\SlackEventsBot\Exceptions\UnsafeMessageSpilloverException;
use HackGreenville\SlackEventsBot\Models\SlackChannel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BotService
{
    public function __construct(
        private DatabaseService $databaseService,
        private MessageBuilderService $messageBuilderService,
        private SlackApiClient $slackApiClient,
    ) {
    }

    public function postOrUpdateMessages(Carbon $week, array $messages): void
    {
        $channels = $this->databaseService->getSlackChannels();
        $existingMessages = $this->databaseService->getMessages($week);

        [$messageDetails, $existingCountByChannel] = $this->buildMessageCache($channels, $existingMessages);

        foreach ($channels as $channel) {
            $slackChannelId = $channel->slack_channel_id;

            if ($this->removeIfOrphaned($channel)) {
                continue;
            }

            try {
                $this->syncChannelMessages($channel, $week, $messages, $messageDetails, $existingCountByChannel);
            } catch (UnsafeMessageSpilloverException $e) {
                Log::error(
                    "Cannot update messages: new events caused message count to increase but next week's post already exists. Cannot resize.",
                    [
                        'week' => $week->format('m/d/Y'),
                        'channel' => $slackChannelId,
                        'existing_count' => $existingCountByChannel[$slackChannelId] ?? 0,
                        'new_count' => count($messages),
                    ]
                );
                throw $e;
            } catch (Exception $e) {
                Log::error("Failed to post/update messages for channel {$slackChannelId}, skipping to next channel", [
                    'channel' => $slackChannelId,
                    'error' => $e->getMessage(),
                ]);
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
        $currentWeekStart = now()->copy()->startOfWeek(Carbon::SUNDAY);

        $this->processWeek($currentWeekStart);

        // Post next week's events 5 days early (starting Tuesday)
        $nextWeekStart = $currentWeekStart->copy()->addWeek();
        $daysUntilNextWeek = now()->diffInDays($nextWeekStart, false);
        if ($daysUntilNextWeek <= 5) {
            $this->processWeek($nextWeekStart);
        }
    }

    protected function getEventsForWeek(Carbon $weekStart): Collection
    {
        return Event::query()
            ->with(['venue', 'organization'])
            ->published()
            ->whereBetween('active_at', [
                $weekStart,
                $weekStart->copy()->endOfWeek(Carbon::SATURDAY),
            ])
            ->oldest('active_at')
            ->get();
    }

    protected function deleteMessagesForWeek(Carbon $week): void
    {
        $messagesToDelete = $this->databaseService->getMessages($week);

        if ($messagesToDelete->isEmpty()) {
            return;
        }

        foreach ($messagesToDelete as $message) {
            if ($this->removeIfOrphaned($message->channel)) {
                continue;
            }

            Log::info("Deleting stale message {$message->message_timestamp} in channel {$message->channel->slack_channel_id}.");
            $this->deleteSlackMessage($message->channel, $message->message_timestamp);
        }
    }

    /**
     * Build lookup dictionaries for existing messages, keyed by channel and sequence position.
     *
     * @return array{0: array<string, array<int, array{timestamp: string, message_text: string}>>, 1: array<string, int>}
     */
    private function buildMessageCache(Collection $channels, Collection $existingMessages): array
    {
        $messageDetails = [];
        $existingCountByChannel = [];

        foreach ($channels as $channel) {
            $channelId = $channel->slack_channel_id;
            $messageDetails[$channelId] = [];
            $channelExistingMessages = $existingMessages->where('channel.slack_channel_id', $channelId);
            $existingCountByChannel[$channelId] = $channelExistingMessages->count();

            foreach ($channelExistingMessages as $existingMessage) {
                $messageDetails[$channelId][$existingMessage->sequence_position] = [
                    'timestamp' => $existingMessage->message_timestamp,
                    'message_text' => $existingMessage->message,
                ];
            }
        }

        return [$messageDetails, $existingCountByChannel];
    }

    /**
     * Synchronize messages for a single channel: post new, update changed, delete excess.
     */
    private function syncChannelMessages(
        SlackChannel $channel,
        Carbon $week,
        array $messages,
        array $messageDetails,
        array $existingCountByChannel,
    ): void {
        $slackChannelId = $channel->slack_channel_id;
        $chat = $this->slackApiClient->chat($channel);
        $existingCount = $existingCountByChannel[$slackChannelId] ?? 0;

        // Check spillover once per channel — the result is constant for all messages
        $spilloverUnsafe = $this->isUnsafeToSpillover($existingCount, count($messages), $week, $slackChannelId);

        foreach ($messages as $msgIdx => $msg) {
            $msgText = $msg['text'];
            $msgBlocks = $msg['blocks'];
            $existingMsgDetail = $messageDetails[$slackChannelId][$msgIdx] ?? null;

            if ($existingMsgDetail && $msgText === $existingMsgDetail['message_text']) {
                Log::info(
                    "Message " . ($msgIdx + 1) . " for week of {$week->format('F j')} " .
                    "in {$slackChannelId} hasn't changed, not updating"
                );

                continue;
            }

            if ($spilloverUnsafe) {
                throw new UnsafeMessageSpilloverException;
            }

            if ( ! $existingMsgDetail) {
                Log::info("Posting new message " . ($msgIdx + 1) . " for week {$week->format('F j')} in {$slackChannelId}");

                $slackResponse = $chat->postMessage($msgBlocks, $msgText);

                $this->databaseService->createMessage(
                    $week,
                    $msgText,
                    $slackResponse['ts'],
                    $slackChannelId,
                    $msgIdx
                );
            } else {
                Log::info(
                    "Updating message " . ($msgIdx + 1) . " for week {$week->format('F j')} " .
                    "in {$slackChannelId} due to text content change."
                );

                $timestamp = $existingMsgDetail['timestamp'];

                $chat->update($timestamp, $msgBlocks, $msgText);

                $this->databaseService->updateMessage(
                    $week,
                    $msgText,
                    $timestamp,
                    $slackChannelId
                );
            }
        }

        // Delete excess messages when the new set has fewer
        $currentMessageCount = count($messageDetails[$slackChannelId] ?? []);
        for ($i = count($messages); $i < $currentMessageCount; $i++) {
            $timestampToDelete = $messageDetails[$slackChannelId][$i]['timestamp'] ?? null;
            if ($timestampToDelete) {
                Log::info("Deleting old message (sequence_position {$i}) for week of {$week->format('F j')} in {$slackChannelId}. No longer needed.");
                $this->deleteSlackMessage($channel, $timestampToDelete);
            }
        }
    }

    private function processWeek(Carbon $weekStart): void
    {
        $events = $this->getEventsForWeek($weekStart);

        if ($events->isEmpty()) {
            Log::info("No upcoming events found for the week of {$weekStart->format('F j')}. Cleaning up any existing messages for this week.");
            $this->deleteMessagesForWeek($weekStart);
        } else {
            $this->parseEventsForWeek($events, $weekStart);
        }
    }

    private function deleteSlackMessage(SlackChannel $channel, string $timestamp): void
    {
        $json = $this->slackApiClient->chat($channel)->delete($timestamp);
        $deleteOk = $json['ok'] ?? false;
        $deleteError = $json['error'] ?? 'unknown';

        $slackChannelId = $channel->slack_channel_id;

        if ($deleteOk || $deleteError === 'message_not_found') {
            $this->databaseService->deleteMessage($slackChannelId, $timestamp);
        } else {
            Log::error("Failed to delete message from Slack", [
                'channel' => $slackChannelId,
                'timestamp' => $timestamp,
                'error' => $deleteError,
            ]);
        }
    }

    /**
     * Check if the channel's workspace has been removed, and clean up if so.
     *
     * Named to make the side effect (channel deletion) explicit at call sites.
     */
    private function removeIfOrphaned(SlackChannel $channel): bool
    {
        if ($channel->workspace) {
            return false;
        }

        $slackChannelId = $channel->slack_channel_id;
        Log::warning("Channel {$slackChannelId} has no associated workspace. Deleting orphaned channel.");
        $this->databaseService->removeChannel($slackChannelId);

        return true;
    }

    /**
     * Determines if it's unsafe to add new messages to a channel for a given week.
     *
     * When the message count increases (e.g. new events added), we need to post additional
     * Slack messages. However, if a newer week's messages already exist in the channel,
     * appending new messages would place them after the newer week — breaking chronological order.
     */
    private function isUnsafeToSpillover(
        int $existingMessagesLength,
        int $newMessagesLength,
        Carbon $week,
        string $slackChannelId
    ): bool {
        if ($newMessagesLength <= $existingMessagesLength || $existingMessagesLength === 0) {
            return false;
        }

        $newestMessageInChannel = $this->databaseService->getMostRecentMessageForChannel($slackChannelId);

        if ( ! $newestMessageInChannel) {
            return false;
        }

        return $newestMessageInChannel->week->greaterThan($week);
    }
}
