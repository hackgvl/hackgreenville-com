<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\OrganizationStatus;
use App\Models\Event;
use App\Models\Org;
use Tests\DatabaseTestCase;

class CalendarFeedTest extends DatabaseTestCase
{
    public function test_index_displays_only_active_organizations(): void
    {
        // Arrange
        $first_org = Org::factory()->create([
            'title' => 'First Active Org',
            'status' => OrganizationStatus::Active,
        ]);

        $second_org = Org::factory()->create([
            'title' => 'Second Active Org',
            'status' => OrganizationStatus::Active,
        ]);

        $inactive_org = Org::factory()->create([
            'title' => 'Inactive Org',
            'status' => OrganizationStatus::InActive,
        ]);

        $this->get(route('calendar-feed.index'))
            ->assertOk()
            ->assertViewIs('calendar-feed.index')
            ->assertViewHas('organizations', fn ($organizations) => $organizations->count() === 2
                    && $organizations->contains('id', $first_org->id)
                    && $organizations->contains('id', $second_org->id)
                    && $organizations->contains('id', $inactive_org->id) === false
                    && $organizations->firstWhere('id', $first_org->id)['checked']
                    && $organizations->firstWhere('id', $second_org->id)['checked']);
    }

    public function test_show_generates_calendar_with_multiple_organizations_and_unique_uids(): void
    {
        $first_org = Org::factory()->create([
            'title' => 'First Organization',
            'status' => OrganizationStatus::Active,
        ]);

        $second_org = Org::factory()->create([
            'title' => 'Second Organization',
            'status' => OrganizationStatus::Active,
        ]);

        // Create multiple events for each organization
        $first_org_events = Event::factory(3)->create([
            'organization_id' => $first_org->id,
            'cancelled_at' => null,
            'active_at' => now(),
            'expire_at' => now()->addHours(2),
            'event_name' => 'Event 1',
        ]);

        $second_org_events = Event::factory(2)->create([
            'organization_id' => $second_org->id,
            'cancelled_at' => null,
            'active_at' => now()->addDay(),
            'expire_at' => now()->addDay()->addHours(2),
            'event_name' => 'Event hello!',
        ]);

        $response = $this->get(route('calendar-feed.show', [
            'orgs' => "{$first_org->id}-{$second_org->id}",
        ]));

        $response->assertOk();

        $calendar_content = $response->getContent();

        // Verify all events are present
        $this->assertEquals(
            5,
            mb_substr_count($calendar_content, 'BEGIN:VEVENT'),
            'Calendar should contain exactly 5 events'
        );

        // Extract all UIDs from the calendar content
        preg_match_all('/UID:(.+)/', $calendar_content, $matches);
        $uids = $matches[1];

        // Verify we have the correct number of UIDs
        $this->assertCount(5, $uids, 'Should have 5 UIDs');

        // Verify all UIDs are unique
        $unique_uids = array_unique($uids);
        $this->assertCount(
            5,
            $unique_uids,
            'All UIDs in the calendar should be unique'
        );

        // Verify each event from both organizations is present
        foreach ($first_org_events as $event) {
            $this->assertStringContainsString(
                "SUMMARY:{$event->event_name}",
                $calendar_content,
                "Event '{$event->event_name}' from first organization should be in calendar"
            );
        }

        foreach ($second_org_events as $event) {
            $this->assertStringContainsString(
                "SUMMARY:{$event->event_name}",
                $calendar_content,
                "Event '{$event->event_name}' from second organization should be in calendar"
            );
        }

        // Verify calendar has multiple organization header
        $this->assertStringContainsString(
            'PRODID:HackGreenville.com Event Calendar',
            $calendar_content,
            'Calendar should have multiple organization header'
        );
    }

    public function test_show_handles_cancelled_events_correctly(): void
    {
        // Arrange
        $organization = Org::factory()->create(['status' => OrganizationStatus::Active]);

        $active_event = Event::factory()->create([
            'organization_id' => $organization->id,
            'cancelled_at' => null,
            'event_name' => 'Active Event',
            'active_at' => now(),
            'expire_at' => now()->addHours(2),
        ]);

        $cancelled_event = Event::factory()->create([
            'organization_id' => $organization->id,
            'cancelled_at' => now(),
            'event_name' => 'Cancelled Event',
            'active_at' => now()->addDay(),
            'expire_at' => now()->addDay()->addHours(2),
        ]);

        $response = $this->get(route('calendar-feed.show', ['orgs' => $organization->id]));

        $calendar_content = $response->getContent();

        $this->assertStringContainsString(
            'STATUS:CONFIRMED',
            $calendar_content,
            'Active event should have CONFIRMED status'
        );

        $this->assertStringContainsString(
            'STATUS:CANCELLED',
            $calendar_content,
            'Cancelled event should have CANCELLED status'
        );

        // Verify both events are present but with different statuses
        $this->assertStringContainsString(
            "SUMMARY:{$active_event->event_name}",
            $calendar_content
        );

        $this->assertStringContainsString(
            "SUMMARY:{$cancelled_event->event_name}",
            $calendar_content
        );
    }

    public function test_show_generates_deterministic_uids(): void
    {
        $organization = Org::factory()->create(['status' => OrganizationStatus::Active]);

        $event = Event::factory()->create([
            'organization_id' => $organization->id,
            'cancelled_at' => null,
            'active_at' => now()->addDay(),
            'expire_at' => now()->addDay(),
        ]);

        // Get calendar content twice
        $first_response = $this->get(route('calendar-feed.show', ['orgs' => $organization->id]));
        $second_response = $this->get(route('calendar-feed.show', ['orgs' => $organization->id]));

        // Extract UIDs from both responses
        preg_match('/UID:(.+)/', $first_response->getContent(), $first_match);
        preg_match('/UID:(.+)/', $second_response->getContent(), $second_match);

        // Assert - Same event should have same UID in different requests
        $this->assertEquals(
            $first_match[1],
            $second_match[1],
            'UIDs should be deterministic for the same event'
        );
    }

    public function test_older_events_never_show_up_on_calendar_feed(): void
    {
        $organization = Org::factory()->create(['status' => OrganizationStatus::Active]);

        $event = Event::factory()->create([
            'organization_id' => $organization->id,
            'cancelled_at' => null,
            'active_at' => now()->subMinute(),
            'expire_at' => now()->subMinute(),
        ]);

        $first_response = $this->get(route('calendar-feed.show', ['orgs' => $organization->id]));

        preg_match('/UID:(.+)/', $first_response->getContent(), $first_match);

        $this->assertFalse(isset($first_match[1]));
    }
}
