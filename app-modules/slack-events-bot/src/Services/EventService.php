<?php

namespace HackGreenville\SlackEventsBot\Services;

use App\Models\Event;
use Illuminate\Support\Str;

class EventService
{
    /**
     * Generate both Slack blocks and plain-text fallback for an event.
     *
     * Computes shared derived values (URL, status, location, time) once,
     * then builds both representations. Prefer this over calling
     * generateBlocks() and generateText() separately.
     */
    public function generate(Event $event): array
    {
        $url = $this->sanitizeUrl($event->url());
        $status = $this->printStatus($event->status());
        $location = $event->venue?->fullAddress() ?? 'No location';
        $time = $event->active_at->format('F j, Y g:i A T');
        $orgTitle = $event->organization?->title ?? 'Unknown Organization';

        $eventName = mb_trim((string) $event->event_name);
        if (empty($eventName)) {
            $eventName = 'Untitled Event';
        }

        $blocks = [
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => Str::limit($eventName, 150),
                ],
            ],
            [
                'type' => 'section',
                'text' => [
                    'type' => 'plain_text',
                    'text' => Str::limit(mb_trim((string) $event->description), 250) ?: 'No description',
                ],
                'fields' => [
                    ['type' => 'mrkdwn', 'text' => '*' . Str::limit($orgTitle) . '*'],
                    ['type' => 'mrkdwn', 'text' => '<' . $url . '|*Link* :link:>'],
                    ['type' => 'mrkdwn', 'text' => '*Status*'],
                    ['type' => 'mrkdwn', 'text' => $status],
                    ['type' => 'mrkdwn', 'text' => '*Location*'],
                    ['type' => 'mrkdwn', 'text' => $location],
                    ['type' => 'mrkdwn', 'text' => '*Time*'],
                    ['type' => 'plain_text', 'text' => $time],
                ],
            ],
        ];

        $text = sprintf(
            "%s\nOrganization: %s\nDescription: %s\nLink: %s\nStatus: %s\nLocation: %s\nTime: %s",
            Str::limit($event->event_name, 250),
            Str::limit($orgTitle, 250),
            Str::limit($event->description, 250),
            $url,
            $status,
            $location,
            $time
        );

        return ['blocks' => $blocks, 'text' => $text];
    }

    public function generateBlocks(Event $event): array
    {
        return $this->generate($event)['blocks'];
    }

    public function generateText(Event $event): string
    {
        return $this->generate($event)['text'];
    }

    private function sanitizeUrl(?string $url): string
    {
        $rawUrl = str_replace(['>', '|'], '', (string) $url);

        return filter_var($rawUrl, FILTER_VALIDATE_URL) ? $rawUrl : '#';
    }

    private function printStatus(string $status): string
    {
        return match ($status) {
            'upcoming' => 'Upcoming ✅',
            'past' => 'Past ✔',
            'cancelled' => 'Cancelled ❌',
            default => ucfirst($status),
        };
    }
}
