<?php

namespace Database\Seeders;

use App\Enums\EventServices;
use App\Enums\EventVisibility;
use App\Models\Event;
use App\Models\Org;
use App\Models\Venue;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run()
    {
        // Load all venues and organizations into collections for easy lookup
        $venues = Venue::all()->keyBy('slug');
        $organizations = Org::all()->keyBy('slug');

        if ($venues->isEmpty()) {
            throw new Exception('No venues found. Please run VenueSeeder first.');
        }

        if ($organizations->isEmpty()) {
            throw new Exception('No organizations found. Please run OrganizationSeeder first.');
        }

        $events = $this->getEventData();

        foreach ($events as $event_data) {
            $this->createEvent($event_data, $organizations, $venues);
        }
    }

    private function createEvent(array $event_data, $organizations, $venues)
    {
        // Extract and validate organization
        $organization = $organizations->get($event_data['org_slug']);
        if (!$organization) {
            throw new Exception("Organization '{$event_data['org_slug']}' not found.");
        }

        // Extract and validate venue
        $venue = $venues->get($event_data['venue_slug']);
        if (!$venue) {
            throw new Exception("Venue '{$event_data['venue_slug']}' not found.");
        }

        // Build the event data
        $event = [
            'organization_id' => $organization->id,
            'venue_id' => $venue->id,
            'event_name' => $event_data['name'],
            'group_name' => $event_data['group_name'] ?? $organization->title,
            'description' => $event_data['description'],
            'active_at' => $this->parseEventTime($event_data['starts_at']),
            'expire_at' => $this->parseEventTime($event_data['ends_at']),
            'service' => $event_data['service'] ?? $organization->service,
            'service_id' => $event_data['service_id'],
            'uri' => $event_data['uri'],
            'rsvp_count' => $event_data['rsvp_count'] ?? 0,
            'is_paid' => $event_data['is_paid'] ?? false,
            'visibility' => $event_data['visibility'] ?? EventVisibility::Published,
        ];

        // Use updateOrCreate to avoid duplicates
        Event::updateOrCreate(
            [
                'service' => $event['service'],
                'service_id' => $event['service_id'],
            ],
            array_merge($event, ['event_uuid' => Str::uuid()->toString()])
        );
    }

    private function parseEventTime($time_spec)
    {
        if ($time_spec instanceof \DateTime) {
            return Carbon::instance($time_spec);
        }

        if (is_array($time_spec)) {
            return Carbon::now()
                ->addDays($time_spec['days'] ?? 0)
                ->setTime($time_spec['hour'] ?? 0, $time_spec['minute'] ?? 0);
        }

        return Carbon::parse($time_spec);
    }

    private function getEventData(): array
    {
        return [
            // HackGreenville Events
            [
                'org_slug' => 'hackgreenville',
                'venue_slug' => 'synergy-mill',
                'name' => 'HackGreenville Monthly Meetup - February 2025',
                'description' => 'Join us for our monthly meetup where we discuss local tech news, upcoming events, and network with fellow developers and tech enthusiasts in the Upstate.',
                'starts_at' => ['days' => 7, 'hour' => 18, 'minute' => 30],
                'ends_at' => ['days' => 7, 'hour' => 20, 'minute' => 30],
                'service_id' => '305890241',
                'uri' => 'https://www.meetup.com/hack-greenville/events/305890241',
                'rsvp_count' => 45,
            ],
            [
                'org_slug' => 'hackgreenville',
                'venue_slug' => 'openworks',
                'name' => 'Code & Coffee Saturday',
                'description' => 'Start your Saturday morning with coffee and code! Join us for casual coding, project sharing, and tech discussions.',
                'starts_at' => ['days' => 14, 'hour' => 9, 'minute' => 0],
                'ends_at' => ['days' => 14, 'hour' => 11, 'minute' => 0],
                'service_id' => '305890242',
                'uri' => 'https://www.meetup.com/hack-greenville/events/305890242',
                'rsvp_count' => 22,
            ],
            [
                'org_slug' => 'hackgreenville',
                'venue_slug' => 'synergy-mill',
                'name' => 'Lightning Talks Night',
                'description' => 'Share your knowledge! Present a 5-minute lightning talk on any tech topic.',
                'starts_at' => ['days' => 21, 'hour' => 18, 'minute' => 30],
                'ends_at' => ['days' => 21, 'hour' => 20, 'minute' => 30],
                'service_id' => '305890243',
                'uri' => 'https://www.meetup.com/hack-greenville/events/305890243',
                'rsvp_count' => 38,
            ],

            // Tech After Five Events
            [
                'org_slug' => 'tech-after-five',
                'venue_slug' => 'openworks',
                'name' => 'Tech After Five - February Networking',
                'description' => 'Join us for the premier tech networking event in Greenville! Connect with entrepreneurs, developers, and tech professionals.',
                'starts_at' => ['days' => 10, 'hour' => 17, 'minute' => 30],
                'ends_at' => ['days' => 10, 'hour' => 20, 'minute' => 0],
                'service_id' => '987654321098',
                'uri' => 'https://www.eventbrite.com/e/tech-after-five-february-tickets-987654321098',
                'rsvp_count' => 120,
                'is_paid' => true,
            ],
            [
                'org_slug' => 'tech-after-five',
                'venue_slug' => 'synergy-mill',
                'name' => 'Tech After Five - March Networking',
                'description' => 'Monthly networking event for the Greenville tech community. Food, drinks, and great conversations!',
                'starts_at' => ['days' => 38, 'hour' => 17, 'minute' => 30],
                'ends_at' => ['days' => 38, 'hour' => 20, 'minute' => 0],
                'service_id' => '987654321099',
                'uri' => 'https://www.eventbrite.com/e/tech-after-five-march-tickets-987654321099',
                'rsvp_count' => 95,
                'is_paid' => true,
            ],

            // Pixel Pushers Events
            [
                'org_slug' => 'pixel-pushers',
                'venue_slug' => 'openworks',
                'name' => 'Modern CSS: Container Queries & Cascade Layers',
                'description' => 'Deep dive into modern CSS features including container queries, cascade layers, and the latest in CSS design patterns.',
                'starts_at' => ['days' => 9, 'hour' => 18, 'minute' => 0],
                'ends_at' => ['days' => 9, 'hour' => 20, 'minute' => 0],
                'service_id' => 'evt-8xKmN3pQR5vZLJ2',
                'uri' => 'https://lu.ma/evt-8xKmN3pQR5vZLJ2',
                'rsvp_count' => 28,
            ],
            [
                'org_slug' => 'pixel-pushers',
                'venue_slug' => 'openworks',
                'name' => 'Figma to Code: Best Practices',
                'description' => 'Learn how to efficiently translate Figma designs into production-ready code with modern tools and techniques.',
                'starts_at' => ['days' => 23, 'hour' => 18, 'minute' => 0],
                'ends_at' => ['days' => 23, 'hour' => 20, 'minute' => 0],
                'service_id' => 'evt-9yLnO4qRS6wAMK3',
                'uri' => 'https://lu.ma/evt-9yLnO4qRS6wAMK3',
                'rsvp_count' => 32,
            ],

            // Carolina Code Conf Events
            [
                'org_slug' => 'carolina-code-conf',
                'venue_slug' => 'synergy-mill',
                'name' => 'Carolina Code Conference 2025',
                'description' => 'Annual conference bringing together developers from across the Carolinas for two days of learning and networking.',
                'starts_at' => ['days' => 60, 'hour' => 8, 'minute' => 0],
                'ends_at' => ['days' => 61, 'hour' => 18, 'minute' => 0],
                'service_id' => 'ccc-2025-main',
                'uri' => 'https://carolina.codes/2025',
                'rsvp_count' => 200,
                'is_paid' => true,
            ],
            [
                'org_slug' => 'carolina-code-conf',
                'venue_slug' => 'openworks',
                'name' => 'Pre-Conference Workshop: Cloud Native Development',
                'description' => 'Full-day workshop on building cloud-native applications with Kubernetes and serverless technologies.',
                'starts_at' => ['days' => 59, 'hour' => 9, 'minute' => 0],
                'ends_at' => ['days' => 59, 'hour' => 17, 'minute' => 0],
                'service_id' => 'ccc-2025-workshop-1',
                'uri' => 'https://carolina.codes/2025/workshops/cloud-native',
                'rsvp_count' => 40,
                'is_paid' => true,
            ],

            // Past event for testing
            [
                'org_slug' => 'hackgreenville',
                'venue_slug' => 'synergy-mill',
                'name' => 'HackGreenville Year End Celebration 2024',
                'description' => 'Celebrated the year with the HackGreenville community! Food, drinks, and great conversations.',
                'starts_at' => ['days' => -30, 'hour' => 18, 'minute' => 0],
                'ends_at' => ['days' => -30, 'hour' => 21, 'minute' => 0],
                'service_id' => '304890240',
                'uri' => 'https://www.meetup.com/hack-greenville/events/304890240',
                'rsvp_count' => 75,
            ],
        ];
    }
}