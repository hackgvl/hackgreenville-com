<?php

namespace HackGreenville\SlackEventsBot\Services;

use Carbon\Carbon;

class EventService
{
    public function createEventFromJson(array $eventData): array
    {
        return [
            'title' => $eventData['event_name'],
            'group_name' => $eventData['group_name'],
            'description' => $eventData['description'],
            'location' => $this->parseLocation($eventData),
            'time' => Carbon::parse($eventData['time']),
            'url' => $eventData['url'],
            'status' => $eventData['status'],
            'uuid' => $eventData['uuid'],
        ];
    }

    public function generateBlocks(array $event): array
    {
        return [
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => $this->truncateString($event['title']),
                ],
            ],
            [
                'type' => 'section',
                'text' => [
                    'type' => 'plain_text',
                    'text' => $this->truncateString($event['description']),
                ],
                'fields' => [
                    ['type' => 'mrkdwn', 'text' => '*' . $this->truncateString($event['group_name']) . '*'],
                    ['type' => 'mrkdwn', 'text' => '<' . $event['url'] . '|*Link* :link:>'],
                    ['type' => 'mrkdwn', 'text' => '*Status*'],
                    ['type' => 'mrkdwn', 'text' => $this->printStatus($event['status'])],
                    ['type' => 'mrkdwn', 'text' => '*Location*'],
                    ['type' => 'mrkdwn', 'text' => $this->getLocationUrl($event['location'])],
                    ['type' => 'mrkdwn', 'text' => '*Time*'],
                    ['type' => 'plain_text', 'text' => $this->printDateTime($event['time'])],
                ],
            ],
        ];
    }

    public function generateText(array $event): string
    {
        return sprintf(
            "%s\nDescription: %s\nLink: %s\nStatus: %s\nLocation: %s\nTime: %s",
            $this->truncateString($event['title']),
            $this->truncateString($event['description']),
            $event['url'],
            $this->printStatus($event['status']),
            $event['location'] ?? 'No location',
            $this->printDateTime($event['time'])
        );
    }
    private function parseLocation(array $eventData): ?string
    {
        $venue = $eventData['venue'] ?? null;

        if ( ! $venue) {
            return null;
        }

        // Full address available
        if (
            isset($venue['name'], $venue['address'], $venue['city'], $venue['state'], $venue['zip']) &&
            ! in_array(null, [$venue['name'], $venue['address'], $venue['city'], $venue['state'], $venue['zip']], true)
        ) {
            return sprintf(
                '%s at %s %s, %s %s',
                $venue['name'],
                $venue['address'],
                $venue['city'],
                $venue['state'],
                $venue['zip']
            );
        }

        // Lat/long available
        if (isset($venue['lat'], $venue['lon']) && $venue['lat'] !== null && $venue['lon'] !== null) {
            return sprintf('lat/long: %s, %s', $venue['lat'], $venue['lon']);
        }

        // Just the name
        return $venue['name'] ?? null;
    }

    private function truncateString(string $string, int $length = 250): string
    {
        if (mb_strlen($string) <= $length) {
            return $string;
        }

        return mb_substr($string, 0, $length) . '...';
    }

    private function getLocationUrl(?string $location): string
    {
        if ( ! $location) {
            return 'No location';
        }

        $encodedLocation = urlencode($location);
        return "<https://www.google.com/maps/search/?api=1&query={$encodedLocation}|{$location}>";
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

    private function printDateTime(Carbon $time): string
    {
        return $time->setTimezone(config('app.timezone'))->format('F j, Y g:i A T');
    }
}
