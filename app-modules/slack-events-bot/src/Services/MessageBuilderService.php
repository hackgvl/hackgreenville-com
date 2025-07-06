<?php

namespace HackGreenville\SlackEventsBot\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MessageBuilderService
{
    public function __construct(private EventService $eventService)
    {
    }

    /**
     * Builds a collection of event blocks for Slack messages.
     *
     * @param Collection<Event> $events A collection of Event models.
     * @return Collection<array> A collection where each item represents an event's blocks and text for Slack.
     */
    public function buildEventBlocks(Collection $events): Collection
    {
        return $events
            ->map(fn (Event $event) => $this->buildSingleEventBlock($event))
            ->filter()
            ->values();
    }

    /**
     * Chunks a collection of event blocks into multiple Slack messages.
     *
     * @param Collection<array> $eventBlocks A collection of arrays, each containing 'blocks', 'text', and 'text_length'.
     * @param Carbon $weekStart The start of the week for header generation.
     * @return array An array of message arrays, each containing 'blocks' and 'text'.
     */
    public function chunkMessages(Collection $eventBlocks, Carbon $weekStart): array
    {
        $messagesNeeded = $this->totalMessagesNeeded($eventBlocks);
        $maxLength = config('slack-events-bot.max_message_character_length');

        $messages = [];
        $initialHeader = $this->buildHeader($weekStart, 1, $messagesNeeded);

        $blocks = $initialHeader['blocks'];
        $text = $initialHeader['text'];

        foreach ($eventBlocks as $event) {
            // Event can be safely added to existing message
            if (($event['text_length'] + mb_strlen($text)) < $maxLength) {
                $blocks = array_merge($blocks, $event['blocks']);
                $text .= $event['text'];
                continue;
            }

            // Save message and then start a new one
            $messages[] = ['blocks' => $blocks, 'text' => $text];

            $newHeader = $this->buildHeader($weekStart, count($messages) + 1, $messagesNeeded);
            $blocks = array_merge($newHeader['blocks'], $event['blocks']);
            $text = $newHeader['text'] . $event['text'];
        }

        // Add whatever is left as a new message
        $messages[] = ['blocks' => $blocks, 'text' => $text];

        return $messages;
    }

    /**
     * Builds the header block and text for a Slack message.
     *
     * @param Carbon $weekStart The start of the week.
     * @param int $index The current message index (e.g., 1 of 3).
     * @param int $total The total number of messages.
     * @return array An array containing 'blocks', 'text', and 'text_length' for the header.
     */
    private function buildHeader(Carbon $weekStart, int $index, int $total): array
    {
        $text = sprintf(
            "HackGreenville Events for the week of %s - %d of %d\n\n===\n\n",
            $weekStart->format('F j'),
            $index,
            $total
        );

        return [
            'blocks' => [
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => sprintf(
                            'HackGreenville Events for the week of %s - %d of %d',
                            $weekStart->format('F j'),
                            $index,
                            $total
                        ),
                    ],
                ],
                ['type' => 'divider'],
            ],
            'text' => $text,
            'text_length' => mb_strlen($text),
        ];
    }

    /**
     * Builds a single event's blocks and text for inclusion in a Slack message.
     *
     * @param Event $event The Event model.
     * @return array|null An array containing 'blocks', 'text', and 'text_length' for the event, or null if the event should be skipped.
     */
    private function buildSingleEventBlock(Event $event): ?array
    {
        $text = $this->eventService->generateText($event) . "\n\n";

        return [
            'blocks' => array_merge($this->eventService->generateBlocks($event), [['type' => 'divider']]),
            'text' => $text,
            'text_length' => mb_strlen($text),
        ];
    }

    /**
     * Calculates the total number of Slack messages needed for the given event blocks.
     *
     * @param Collection<array> $eventBlocks A collection of arrays, each containing 'blocks', 'text', and 'text_length'.
     * @return int The total number of messages required.
     */
    private function totalMessagesNeeded(Collection $eventBlocks): int
    {
        $maxLength = config('slack-events-bot.max_message_character_length');
        $headerBuffer = config('slack-events-bot.header_buffer_length');

        $totalLength = $eventBlocks->sum('text_length');
        $messagesNeeded = (int) ceil($totalLength / ($maxLength - $headerBuffer));

        // Ensure total count is at least 1 if we're going to post anything
        return max(1, $messagesNeeded);
    }
}
