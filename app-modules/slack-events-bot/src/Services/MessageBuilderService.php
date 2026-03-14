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
        $maxLength = config('slack-events-bot.max_message_character_length');

        // Use a sample header to get the actual header text length for accurate chunking
        $sampleHeader = $this->buildHeader($weekStart, 1, 1);
        $headerLength = $sampleHeader['text_length'];

        // First pass: chunk events into messages using actual header length
        $chunked = [];
        $currentEvents = [];
        $currentTextLength = $headerLength;

        foreach ($eventBlocks as $event) {
            // Skip events that alone exceed the max message length
            if ($event['text_length'] + $headerLength >= $maxLength) {
                continue;
            }

            if (($event['text_length'] + $currentTextLength) < $maxLength) {
                $currentEvents[] = $event;
                $currentTextLength += $event['text_length'];
                continue;
            }

            $chunked[] = $currentEvents;
            $currentEvents = [$event];
            $currentTextLength = $headerLength + $event['text_length'];
        }

        $chunked[] = $currentEvents;
        $totalMessages = count($chunked);

        // Second pass: build final messages with correct "X of Y" headers
        $messages = [];
        foreach ($chunked as $index => $events) {
            $header = $this->buildHeader($weekStart, $index + 1, $totalMessages);
            $blocks = $header['blocks'];
            $text = $header['text'];

            foreach ($events as $event) {
                $blocks = array_merge($blocks, $event['blocks']);
                $text .= $event['text'];
            }

            $messages[] = ['blocks' => $blocks, 'text' => $text];
        }

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

        if (empty(mb_trim($text))) { // Check if text is empty or just whitespace
            return null;
        }

        return [
            'blocks' => array_merge($this->eventService->generateBlocks($event), [['type' => 'divider']]),
            'text' => $text,
            'text_length' => mb_strlen($text),
        ];
    }

}
