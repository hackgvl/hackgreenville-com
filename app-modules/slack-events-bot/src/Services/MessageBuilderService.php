<?php

namespace HackGreenville\SlackEventsBot\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class MessageBuilderService
{
    public function __construct(private EventService $eventService)
    {
    }

    public function buildEventBlocks(array $events, Carbon $weekStart, Carbon $weekEnd): Collection
    {
        return collect($events)
            ->map(fn ($event) => $this->buildSingleEventBlock($event, $weekStart, $weekEnd))
            ->filter()
            ->values();
    }

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
            if ($event['text_length'] + mb_strlen($text) < $maxLength) {
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

    private function buildSingleEventBlock(array $eventData, Carbon $weekStart, Carbon $weekEnd): ?array
    {
        $event = $this->eventService->createEventFromJson($eventData);

        // Ignore event if it's not in the current week
        if ($event['time']->lt($weekStart) || $event['time']->gt($weekEnd)) {
            return null;
        }

        // Ignore event if it has a non-supported status
        if ( ! in_array($event['status'], ['cancelled', 'upcoming', 'past'])) {
            logger()->warning("Couldn't parse event {$event['uuid']} with status: {$event['status']}");
            return null;
        }

        $text = $this->eventService->generateText($event) . "\n\n";

        return [
            'blocks' => array_merge($this->eventService->generateBlocks($event), [['type' => 'divider']]),
            'text' => $text,
            'text_length' => mb_strlen($text),
        ];
    }

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
