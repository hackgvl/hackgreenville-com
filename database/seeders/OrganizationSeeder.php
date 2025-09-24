<?php

namespace Database\Seeders;

use App\Enums\EventServices;
use App\Enums\OrganizationStatus;
use App\Models\Org;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $organizations = [
            // MeetupGraphql service
            [
                'title' => 'HackGreenville',
                'slug' => 'hackgreenville',
                'category_id' => 2, // Meetup Groups
                'path' => 'https://data.openupstate.org/organization/hackgreenville',
                'city' => 'Greenville',
                'focus_area' => 'Connecting and Promoting Local Tech',
                'uri' => 'https://hackgreenville.com',
                'primary_contact_person' => 'Pamela Wood Browne',
                'organization_type' => 'Meetup Groups',
                'event_calendar_uri' => 'https://www.meetup.com/Hack-Greenville/',
                'service' => EventServices::MeetupGraphql,
                'status' => OrganizationStatus::Active,
                'service_api_key' => 'Hack-Greenville',
                'established_at' => '2015-01-01',
            ],
            // EventBrite service
            [
                'title' => 'Tech After Five',
                'slug' => 'tech-after-five',
                'category_id' => 2, // Meetup Groups
                'path' => 'https://data.openupstate.org/organization/tech-after-five',
                'city' => 'Greenville',
                'focus_area' => 'Networking',
                'uri' => 'https://www.techafterfive.com/greenville/',
                'primary_contact_person' => 'Phil Yanov',
                'organization_type' => 'Meetup Groups',
                'event_calendar_uri' => 'https://www.eventbrite.com/o/tech-after-five-444331701',
                'service' => EventServices::EventBrite,
                'status' => OrganizationStatus::Active,
                'service_api_key' => '444331701',
                'established_at' => '2008-01-01',
            ],
            // Luma service
            [
                'title' => 'Pixel Pushers',
                'slug' => 'pixel-pushers',
                'category_id' => 2, // Meetup Groups
                'path' => null,
                'city' => 'Greenville',
                'focus_area' => 'Web Creators / Design',
                'uri' => null,
                'primary_contact_person' => 'Benten Woodring',
                'organization_type' => 'Meetup Groups',
                'event_calendar_uri' => 'https://lu.ma/pixelpushers',
                'service' => EventServices::Luma,
                'status' => OrganizationStatus::Active,
                'service_api_key' => 'cal-kmfIhQsKoWIuNab',
                'established_at' => '2024-02-01',
            ],
            // ManuallyManaged service
            [
                'title' => 'Carolina Code Conf',
                'slug' => 'carolina-code-conf',
                'category_id' => 3, // Conferences / Festivals / Hackathons
                'path' => 'https://data.openupstate.org/organization/carolina-code-conf',
                'city' => 'Greenville',
                'focus_area' => 'Polygot',
                'uri' => 'https://blog.carolina.codes',
                'primary_contact_person' => 'Barry Jones',
                'organization_type' => 'Conferences / Festivals / Hackathons',
                'event_calendar_uri' => 'https://blog.carolina.codes/about',
                'service' => EventServices::ManuallyManaged,
                'status' => OrganizationStatus::Active,
                'service_api_key' => null,
                'established_at' => '2018-01-01',
            ],
        ];

        foreach ($organizations as $org) {
            Org::updateOrCreate(
                ['slug' => $org['slug']],
                $org
            );
        }
    }
}
