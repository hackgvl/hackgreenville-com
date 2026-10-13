<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\OrganizationStatus;
use App\Models\Event;
use App\Models\Org;
use Tests\DatabaseTestCase;

class OrgsTest extends DatabaseTestCase
{
    public function test_show_inactive_org_without_inactive_date_shows_inactive_badge_without_year(): void
    {
        // Arrange
        $inactive_org = Org::factory()->create([
            'status' => OrganizationStatus::Inactive,
            'inactive_at' => null,
        ]);

        // Act
        $response = $this->get(route('orgs.show', $inactive_org));

        // Assert
        $response->assertStatus(200);
        $this->assertSame('Inactive', $this->textOfTestId($response, 'org-inactive-badge'));
    }

    public function test_show_inactive_org_with_inactive_date_shows_inactive_badge_with_year(): void
    {
        // Arrange
        $inactive_org = Org::factory()->create([
            'status' => OrganizationStatus::Inactive,
            'inactive_at' => '2026-08-01',
        ]);

        // Act
        $response = $this->get(route('orgs.show', $inactive_org));

        // Assert
        $response->assertStatus(200);
        $this->assertSame('Inactive as of 2026', $this->textOfTestId($response, 'org-inactive-badge'));
    }

    public function test_show_active_org_hides_inactive_label(): void
    {
        // Arrange
        $active_org = Org::factory()->create([
            'status' => OrganizationStatus::Active,
        ]);

        // Act
        $response = $this->get(route('orgs.show', $active_org));

        // Assert
        $response->assertStatus(200);
        $this->assertNull($this->textOfTestId($response, 'org-inactive-badge'));
    }

    public function test_show_inactive_org_hides_calendar_feed_subscribe_link(): void
    {
        // Arrange
        $inactive_org = Org::factory()->create([
            'status' => OrganizationStatus::Inactive,
        ]);

        // Act
        $response = $this->get(route('orgs.show', $inactive_org));

        // Assert
        $response->assertStatus(200);
        $response->assertDontSee('data-testid="org-calendar-feed-subscribe-link"', false);
    }

    public function test_show_active_org_shows_calendar_feed_subscribe_link(): void
    {
        // Arrange
        $active_org = Org::factory()->create([
            'status' => OrganizationStatus::Active,
        ]);

        // Act
        $response = $this->get(route('orgs.show', $active_org));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('data-testid="org-calendar-feed-subscribe-link"', false);
    }

    public function test_show_inactive_org_hides_upcoming_events(): void
    {
        // Arrange
        $inactive_org = Org::factory()->create([
            'status' => OrganizationStatus::Inactive,
        ]);
        $event = Event::factory()->create([
            'organization_id' => $inactive_org->id,
            'active_at' => now()->addDays(1),
        ]);

        // Act
        $response = $this->get(route('orgs.show', $inactive_org));

        // Assert
        $response->assertStatus(200);
        $response->assertDontSee('data-testid="org-upcoming-events"', false);
    }

    public function test_show_active_org_shows_upcoming_events(): void
    {
        // Arrange
        $active_org = Org::factory()->create([
            'status' => OrganizationStatus::Active,
        ]);
        $event = Event::factory()->create([
            'organization_id' => $active_org->id,
            'active_at' => now()->addDays(1),
        ]);

        // Act
        $response = $this->get(route('orgs.show', $active_org));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('data-testid="org-upcoming-events"', false);
    }
}
