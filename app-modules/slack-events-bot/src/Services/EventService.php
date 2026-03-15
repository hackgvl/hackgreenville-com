<?php

namespace HackGreenville\SlackEventsBot\Services;

use App\Models\Event;
use Illuminate\Support\Str;

class EventService
{
    public function generateBlocks(Event $event): array
    {
        $eventName = mb_trim((string) $event->event_name);
        if (empty($eventName)) {
            $eventName = 'Untitled Event';
        }
        $limitedEventName = Str::limit($eventName, 150);

        $description = Str::limit($event->description, 250);
        if (empty($description)) {
            $description = 'No description';
        }

        $sanitizedUrl = $this->sanitizeUrl($event->url());

        return [
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => $limitedEventName,
                ],
            ],
            [
                'type' => 'section',
                'text' => [
                    'type' => 'plain_text',
                    'text' => $description,
                ],
                'fields' => [
                    ['type' => 'mrkdwn', 'text' => '*' . Str::limit($event->organization?->title ?? 'Unknown Organization') . '*'],
                    ['type' => 'mrkdwn', 'text' => '<' . $sanitizedUrl . '|*Link* :link:>'],
                    ['type' => 'mrkdwn', 'text' => '*Status*'],
                    ['type' => 'mrkdwn', 'text' => $this->printStatus($event->status())],
                    ['type' => 'mrkdwn', 'text' => '*Location*'],
                    ['type' => 'mrkdwn', 'text' => $event->venue ? $event->venue->fullAddress() : 'No location'],
                    ['type' => 'mrkdwn', 'text' => '*Time*'],
                    ['type' => 'plain_text', 'text' => $event->active_at->format('F j, Y g:i A T')],
                ],
            ],
        ];
    }

    public function generateText(Event $event): string
    {
        $sanitizedUrl = $this->sanitizeUrl($event->url());

        return sprintf(
            "%s\nOrganization: %s\nDescription: %s\nLink: %s\nStatus: %s\nLocation: %s\nTime: %s",
            Str::limit($event->event_name, 250),
            Str::limit($event->organization?->title ?? 'Unknown Organization', 250),
            Str::limit($event->description, 250),
            $sanitizedUrl,
            $this->printStatus($event->status()),
            $event->venue ? $event->venue->fullAddress() : 'No location',
            $event->active_at->format('F j, Y g:i A T')
        );
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
