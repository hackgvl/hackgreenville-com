<?php

namespace HackGreenville\SlackEventsBot\Services;

use App\Models\Event;
use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Exceptions\UnsafeMessageSpilloverException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BotService
{
    public function __construct(
        private DatabaseService $databaseService,
        private MessageBuilderService $messageBuilderService,
        private EventService $eventService
    ) {
    }

    public function postOrUpdateMessages(Carbon $week, array $messages): void
    {
        $channels = $this->databaseService->getSlackChannelIds();
        $existingMessages = $this->databaseService->getMessages($week);

        // Group existing messages by channel
        $messageDetails = [];
        foreach ($existingMessages as $existingMessage) {
            $channelId = $existingMessage['slack_channel_id'];
            if ( ! isset($messageDetails[$channelId])) {
                $messageDetails[$channelId] = [];
            }
            $messageDetails[$channelId][] = [
                'timestamp' => $existingMessage['message_timestamp'],
                'message' => $existingMessage['message'],
            ];
        }

        $postedChannelsSet = array_keys($messageDetails);

        foreach ($channels as $slackChannelId) {
            try {
                foreach ($messages as $msgIdx => $msg) {
                    $msgText = $msg['text'];
                    $msgBlocks = $msg['blocks'];

                    // If new events now warrant additional messages being posted
                    if ($msgIdx > count($messageDetails[$slackChannelId] ?? []) - 1) {
                        if ($this->isUnsafeToSpillover(
                            count($existingMessages),
                            count($messages),
                            $week,
                            $slackChannelId
                        )) {
                            throw new UnsafeMessageSpilloverException;
                        }

                        Log::info("Posting an additional message for week {$week->format('F j')} in {$slackChannelId}");

                        $slackResponse = $this->postNewMessage($slackChannelId, $msgBlocks, $msgText);

                        $this->databaseService->createMessage(
                            $week,
                            $msgText,
                            $slackResponse['ts'],
                            $slackChannelId,
                            $msgIdx
                        );
                    } elseif (
                        in_array($slackChannelId, $postedChannelsSet) &&
                        $msgText === $messageDetails[$slackChannelId][$msgIdx]['message']
                    ) {
                        Log::info(
                            "Message " . ($msgIdx + 1) . " for week of {$week->format('F j')} " .
                            "in {$slackChannelId} hasn't changed, not updating"
                        );
                    } elseif (in_array($slackChannelId, $postedChannelsSet)) {
                        if ($this->isUnsafeToSpillover(
                            count($existingMessages),
                            count($messages),
                            $week,
                            $slackChannelId
                        )) {
                            throw new UnsafeMessageSpilloverException;
                        }

                        Log::info(
                            "Updating message " . ($msgIdx + 1) . " for week {$week->format('F j')} " .
                            "in {$slackChannelId}"
                        );

                        $timestamp = $messageDetails[$slackChannelId][$msgIdx]['timestamp'];

                        $response = Http::withToken(config('slack-events-bot.bot_token'))
                            ->post('https://slack.com/api/chat.update', [
                                'ts' => $timestamp,
                                'channel' => $slackChannelId,
                                'blocks' => $msgBlocks,
                                'text' => $msgText,
                            ]);

                        $this->databaseService->updateMessage($week, $msgText, $timestamp, $slackChannelId);
                    } else {
                        Log::info(
                            "Posting message " . ($msgIdx + 1) . " for week {$week->format('F j')} " .
                            "in {$slackChannelId}"
                        );

                        $slackResponse = $this->postNewMessage($slackChannelId, $msgBlocks, $msgText);

                        $this->databaseService->createMessage(
                            $week,
                            $msgText,
                            $slackResponse['ts'],
                            $slackChannelId,
                            $msgIdx
                        );
                    }
                }
            } catch (UnsafeMessageSpilloverException $e) {
                Log::error(
                    "Cannot update messages for {$week->format('m/d/Y')} for channel {$slackChannelId}. " .
                    "New events have caused the number of messages needed to increase, " .
                    "but the next week's post has already been sent. Cannot resize. " .
                    "Existing message count: " . count($existingMessages) . " --- New message count: " . count($messages)
                );
                continue;
            }
        }
    }

    public function parseEventsForWeek(Carbon $probeDate, Collection $events): void
    {
        $weekStart = $probeDate->copy()->startOfWeek()->subDay(); // Sunday
        $weekEnd = $weekStart->copy()->addDays(7);

        // Convert Event models to array format expected by MessageBuilderService
        $eventsArray = $events->map(fn (Event $event) => [
            'event_name' => $event->event_name,
            'group_name' => $event->group_name,
            'description' => $event->description,
            'venue' => $event->venue ? [
                'name' => $event->venue->name,
                'address' => $event->venue->address,
                'city' => $event->venue->city,
                'state' => $event->venue->state?->abbr,
                'zip' => $event->venue->zipcode,
                'lat' => $event->venue->lat,
                'lon' => $event->venue->lng,
            ] : null,
            'time' => $event->active_at->toIso8601String(),
            'url' => $event->uri,
            'status' => $event->status,
            'uuid' => $event->event_uuid,
        ])->toArray();

        $eventBlocks = $this->messageBuilderService->buildEventBlocks($eventsArray, $weekStart, $weekEnd);
        $chunkedMessages = $this->messageBuilderService->chunkMessages($eventBlocks, $weekStart);

        $this->postOrUpdateMessages($weekStart, $chunkedMessages);
    }

    public function checkApi(): void
    {
        // Get events directly from the database instead of API
        $events = Event::query()
            ->with(['venue.state', 'organization'])
            ->published()
            ->where('active_at', '>=', now()->subDays(config('slack-events-bot.days_to_look_back', 1)))
            ->where('active_at', '<=', now()->addDays(config('slack-events-bot.days_to_look_ahead', 14)))
            ->oldest('active_at')
            ->get();

        // Get timezone aware today
        $today = Carbon::today();

        // Keep current week's post up to date
        $this->parseEventsForWeek($today, $events);

        // Potentially post next week 5 days early
        $probeDate = $today->copy()->addDays(5);
        $this->parseEventsForWeek($probeDate, $events);
    }

    private function isUnsafeToSpillover(
        int $existingMessagesLength,
        int $newMessagesLength,
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
        $response = Http::withToken(config('slack-events-bot.bot_token'))
            ->post('https://slack.com/api/chat.postMessage', [
                'channel' => $slackChannelId,
                'blocks' => $msgBlocks,
                'text' => $msgText,
                'unfurl_links' => false,
                'unfurl_media' => false,
            ]);

        return $response->json();
    }
}
