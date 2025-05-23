<?php

namespace HackGreenville\SlackEventsBot\Services;

use App\Models\Event;
use Illuminate\Support\Str;

class EventService
{
    public function generateBlocks(Event $event): array
    {
        return [
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => Str::limit($event->title, 250),
                ],
            ],
            [
                'type' => 'section',
                'text' => [
                    'type' => 'plain_text',
                    'text' => Str::limit($event->description, 250),
                ],
                'fields' => [
                    ['type' => 'mrkdwn', 'text' => '*' . Str::limit($event->organization->title) . '*', 250],
                    ['type' => 'mrkdwn', 'text' => '<' . $event->url . '|*Link* :link:>'],
                    ['type' => 'mrkdwn', 'text' => '*Status*'],
                    ['type' => 'mrkdwn', 'text' => $this->printStatus($event->status)],
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
        return sprintf(
            "%s\nDescription: %s\nLink: %s\nStatus: %s\nLocation: %s\nTime: %s",
            Str::limit($event->title, 250),
            Str::limit($event->description, 250),
            $event['url'],
            $this->printStatus($event->status),
            $event->venue?->name ?? 'No location',
            $event->active_at->format('F j, Y g:i A T')
        );
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
