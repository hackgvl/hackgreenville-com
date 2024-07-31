<?php

namespace HackGreenville\EventImporter\Tests\Feature;

use App\Enums\EventServices;
use App\Models\Event;
use App\Models\Org;
use HackGreenville\EventImporter\Console\Commands\ImportEventsCommand;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\DatabaseTestCase;

class MeetupRestTest extends DatabaseTestCase
{
    public function test_active_meetup_event_is_imported_correctly(): void
    {
        Carbon::setTestNow('2020-01-01');

        Http::fake([
            $this->getMeetupUrl('code-for-the-carolinas-greenville') => Http::response(
                $this->apiResponse('online-event-in-future.json'),
                200
            ),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::MeetupRest,
            'service_api_key' => 'code-for-the-carolinas-greenville',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $active_event = Event::query()->where('event_uuid', '11d0362255a5cc64693968c035892576')->firstOrFail();

        $this->assertEquals('Civic Hacking for Affordable Housing', $active_event->event_name);
        $this->assertEquals($organization->title, $active_event->group_name);
        $this->assertStringContainsString(<<<HTML
<p>Join volunteers at Code for the Carolinas as we help the [National Zoning Atlas](<a href="https://www.zoningatlas.org/" class="linkified">https://www.zoningatlas.org/</a>) (NZA) team include North Carolina and South Carolina in their nationwide zoning atlas. "The National Zoning Atlas aims to depict key aspects of zoning codes in an online, user-friendly map." The Atlas aims to provide data for better solutions in transportation, environmental, and especially affordable housing policy.</p> <p>This is a multi-dimensional project grounded in data science with an end-goal of contributing to a nationally influential dataset. Our current work session activities focus on internet search and integrating data from multiple sources. The project as a whole has occasional opportunities for U/X and outreach and opportunities to learn about machine learning and natural language processing through the work of the NZA team. Volunteering on this project will provide excellent exposure to the foundations of civic data science, with a focus on GIS data.</p> <p>Even if you're new to this project and/or civic tech volunteering, you're welcome to join the meeting. Visitors are always welcome and no technical skills are needed. You can learn more about the project on our [Slack Workspace](<a href="https://join.slack.com/t/codeforthecarolinas/shared_invite/zt-1kxuwu05x-3KxOpkOYjAuN5yuOAH8ROg" class="linkified">https://join.slack.com/t/codeforthecarolinas/shared_invite/zt-1kxuwu05x-3KxOpkOYjAuN5yuOAH8ROg</a>) in the #project-zoning-atlas channel.</p> <p>Meetings start with brief introductions. New volunteers are offered a personal orientation, often in a breakout room, while experienced volunteers get right to work. By the end of the session, both groups of volunteers are working together. We also contribute to the project through asynchronous work and informal work sessions.</p> <p>Meetings are on the Jitsi platform and the link is provided before each meeting. If you will be joining on mobile, install the Jitsi app in advance [<a href="https://jitsi.org/downloads/](https://jitsi.org/downloads/" class="linkified">https://jitsi.org/downloads/](https://jitsi.org/downloads/</a>) .<br/>Learn more at [codeforthecarolinas.org](<a href="http://codeforthecarolinas.org/" class="linkified">http://codeforthecarolinas.org/</a>) or reach out to [masked]</p> <p>Meetings follow the [Open Collective Community Guidelines](<a href="https://docs.opencollective.com/help/about/the-open-collective-way/community-guidelines" class="linkified">https://docs.opencollective.com/help/about/the-open-collective-way/community-guidelines</a>) as a Code of Conduct.</p>
HTML, $active_event->description);

        $this->assertEquals(1, $active_event->rsvp_count);
        $this->assertEquals(1702944000, $active_event->active_at->utc()->unix());
        $this->assertEquals(1702951200, $active_event->expire_at->utc()->unix());
        $this->assertEquals('https://www.meetup.com/code-for-the-carolinas-greenville/events/lvgwftyfcqbxb/', $active_event->uri);
        $this->assertNull($active_event->venue_id);
        $this->assertEquals('upcoming', $active_event->status);
        $this->assertNull($active_event->cancelled_at);

    }

    public function test_cancelled_meetup_event_is_imported_correctly(): void
    {
        Http::fake([
            $this->getMeetupUrl('beer-and-napkins-community-of-design') => Http::response(
                $this->apiResponse('cancelled-event.json'),
                200
            ),
        ]);

        $organization = Org::factory()->create([
            'service' => EventServices::MeetupRest,
            'service_api_key' => 'beer-and-napkins-community-of-design',
        ]);

        $this->artisan(ImportEventsCommand::class);

        $cancelled_event = Event::query()->where('event_uuid', 'ef6d125478d91ff962d92f8f36ca0b26')->firstOrFail();

        $this->assertEquals('cancelled', $cancelled_event->status);
        $this->assertNotNull($cancelled_event->cancelled_at);
    }

    protected function getMeetupUrl(string $service_api_key): string
    {
        return 'https://api.meetup.com/' . $service_api_key . '/events?sign=1&photo-host=public' .
        '&status=upcoming%2Ccancelled%2Cpast&page=100&' .
        'no_earlier_than=' . now()->subDays(env('EVENT_IMPORTER_MAX_DAYS_IN_PAST', 30))->format('Y-m-d') . 'T00%3A00%3A00' .
        '&no_later_than=' . now()->addDays(180)->format('Y-m-d') . 'T23%3A59%3A59';
    }

    protected function apiResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/meetup-rest/' . $file);
    }
}
