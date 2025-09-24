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
        $synergyMill = Venue::where('slug', 'synergy-mill')->first();
        $openWorks = Venue::where('slug', 'openworks')->first();
        $organizations = Org::all()->keyBy('title');

        $events = [
            // HackGreenville Events (MeetupGraphql)
            [
                'organization' => 'HackGreenville',
                'event_name' => 'HackGreenville Monthly Meetup - February 2025',
                'group_name' => 'HackGreenville',
                'description' => 'Join us for our monthly meetup where we discuss local tech news, upcoming events, and network with fellow developers and tech enthusiasts in the Upstate.',
                'active_at' => Carbon::now()->addDays(7)->setTime(18, 30),
                'expire_at' => Carbon::now()->addDays(7)->setTime(20, 30),
                'service' => EventServices::MeetupGraphql,
                'service_id' => '305890241',  // Real-looking Meetup event ID format
                'uri' => 'https://www.meetup.com/hack-greenville/events/305890241',
                'rsvp_count' => 45,
                'visibility' => EventVisibility::Published,
            ],
            [
                'organization' => 'HackGreenville',
                'event_name' => 'Code & Coffee Saturday',
                'group_name' => 'HackGreenville',
                'description' => 'Start your Saturday morning with coffee and code! Join us for casual coding, project sharing, and tech discussions.',
                'active_at' => Carbon::now()->addDays(14)->setTime(9, 0),
                'expire_at' => Carbon::now()->addDays(14)->setTime(11, 0),
                'service' => EventServices::MeetupGraphql,
                'service_id' => '305890242',
                'uri' => 'https://www.meetup.com/hack-greenville/events/305890242',
                'rsvp_count' => 22,
                'visibility' => EventVisibility::Published,
            ],
            [
                'organization' => 'HackGreenville',
                'event_name' => 'Lightning Talks Night',
                'group_name' => 'HackGreenville',
                'description' => 'Share your knowledge! Present a 5-minute lightning talk on any tech topic.',
                'active_at' => Carbon::now()->addDays(21)->setTime(18, 30),
                'expire_at' => Carbon::now()->addDays(21)->setTime(20, 30),
                'service' => EventServices::MeetupGraphql,
                'service_id' => '305890243',
                'uri' => 'https://www.meetup.com/hack-greenville/events/305890243',
                'rsvp_count' => 38,
                'visibility' => EventVisibility::Published,
            ],

            // Tech After Five Events (EventBrite)
            [
                'organization' => 'Tech After Five',
                'event_name' => 'Tech After Five - February Networking',
                'group_name' => 'Tech After Five',
                'description' => 'Join us for the premier tech networking event in Greenville! Connect with entrepreneurs, developers, and tech professionals.',
                'active_at' => Carbon::now()->addDays(10)->setTime(17, 30),
                'expire_at' => Carbon::now()->addDays(10)->setTime(20, 0),
                'service' => EventServices::EventBrite,
                'service_id' => '987654321098',  // Real-looking EventBrite event ID format
                'uri' => 'https://www.eventbrite.com/e/tech-after-five-february-tickets-987654321098',
                'rsvp_count' => 120,
                'is_paid' => true,
                'visibility' => EventVisibility::Published,
            ],
            [
                'organization' => 'Tech After Five',
                'event_name' => 'Tech After Five - March Networking',
                'group_name' => 'Tech After Five',
                'description' => 'Monthly networking event for the Greenville tech community. Food, drinks, and great conversations!',
                'active_at' => Carbon::now()->addDays(38)->setTime(17, 30),
                'expire_at' => Carbon::now()->addDays(38)->setTime(20, 0),
                'service' => EventServices::EventBrite,
                'service_id' => '987654321099',
                'uri' => 'https://www.eventbrite.com/e/tech-after-five-march-tickets-987654321099',
                'rsvp_count' => 95,
                'is_paid' => true,
                'visibility' => EventVisibility::Published,
            ],

            // Pixel Pushers Events (Luma)
            [
                'organization' => 'Pixel Pushers',
                'event_name' => 'Modern CSS: Container Queries & Cascade Layers',
                'group_name' => 'Pixel Pushers',
                'description' => 'Deep dive into modern CSS features including container queries, cascade layers, and the latest in CSS design patterns.',
                'active_at' => Carbon::now()->addDays(9)->setTime(18, 0),
                'expire_at' => Carbon::now()->addDays(9)->setTime(20, 0),
                'service' => EventServices::Luma,
                'service_id' => 'evt-8xKmN3pQR5vZLJ2',  // Real-looking Luma event ID format
                'uri' => 'https://lu.ma/evt-8xKmN3pQR5vZLJ2',
                'rsvp_count' => 28,
                'visibility' => EventVisibility::Published,
            ],
            [
                'organization' => 'Pixel Pushers',
                'event_name' => 'Figma to Code: Best Practices',
                'group_name' => 'Pixel Pushers',
                'description' => 'Learn how to efficiently translate Figma designs into production-ready code with modern tools and techniques.',
                'active_at' => Carbon::now()->addDays(23)->setTime(18, 0),
                'expire_at' => Carbon::now()->addDays(23)->setTime(20, 0),
                'service' => EventServices::Luma,
                'service_id' => 'evt-9yLnO4qRS6wAMK3',
                'uri' => 'https://lu.ma/evt-9yLnO4qRS6wAMK3',
                'rsvp_count' => 32,
                'visibility' => EventVisibility::Published,
            ],

            // Carolina Code Conf Events (ManuallyManaged)
            [
                'organization' => 'Carolina Code Conf',
                'event_name' => 'Carolina Code Conference 2025',
                'group_name' => 'Carolina Code Conf',
                'description' => 'Annual conference bringing together developers from across the Carolinas for two days of learning and networking.',
                'active_at' => Carbon::now()->addDays(60)->setTime(8, 0),
                'expire_at' => Carbon::now()->addDays(61)->setTime(18, 0),
                'service' => EventServices::ManuallyManaged,
                'service_id' => 'ccc-2025-main',
                'uri' => 'https://carolina.codes/2025',
                'rsvp_count' => 200,
                'is_paid' => true,
                'visibility' => EventVisibility::Published,
            ],
            [
                'organization' => 'Carolina Code Conf',
                'event_name' => 'Pre-Conference Workshop: Cloud Native Development',
                'group_name' => 'Carolina Code Conf',
                'description' => 'Full-day workshop on building cloud-native applications with Kubernetes and serverless technologies.',
                'active_at' => Carbon::now()->addDays(59)->setTime(9, 0),
                'expire_at' => Carbon::now()->addDays(59)->setTime(17, 0),
                'service' => EventServices::ManuallyManaged,
                'service_id' => 'ccc-2025-workshop-1',
                'uri' => 'https://carolina.codes/2025/workshops/cloud-native',
                'rsvp_count' => 40,
                'is_paid' => true,
                'visibility' => EventVisibility::Published,
            ],

            // Past event for testing
            [
                'organization' => 'HackGreenville',
                'event_name' => 'HackGreenville Year End Celebration 2024',
                'group_name' => 'HackGreenville',
                'description' => 'Celebrated the year with the HackGreenville community! Food, drinks, and great conversations.',
                'active_at' => Carbon::now()->subDays(30)->setTime(18, 0),
                'expire_at' => Carbon::now()->subDays(30)->setTime(21, 0),
                'service' => EventServices::MeetupGraphql,
                'service_id' => '304890240',
                'uri' => 'https://www.meetup.com/hack-greenville/events/304890240',
                'rsvp_count' => 75,
                'visibility' => EventVisibility::Published,
            ],
        ];

        foreach ($events as $eventData) {
            $organization = $organizations->get($eventData['organization']);

            if ( ! $organization) {
                throw new Exception("Organization '{$eventData['organization']}' not found. Please run OrganizationSeeder first.");
            }

            if ( ! $venue) {
                throw new Exception("Venue not found. Please run VenueSeeder first.");
            }

            unset($eventData['organization']);

            $eventData['organization_id'] = $organization->id;
            $eventData['venue_id'] = $venue->id;
            $eventData['event_uuid'] = Str::uuid()->toString();

            Event::create($eventData);
        }
    }
}
