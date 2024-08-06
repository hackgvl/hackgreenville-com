<?php

namespace HackGreenville\Api\Tests\Feature;

use App\Models\Org;
use App\Models\Tag;
use Tests\DatabaseTestCase;

class OrganizationsApiV0Test extends DatabaseTestCase
{
    public function test_organization_api_v0_return_correct_data(): void
    {
        // Lock-in the time
        $this->travelTo(now());

        $org = Org::factory()->create();

        Tag::factory()->create()->orgs()->attach($org);

        $this->getJson(route('api.v0.orgs.index'))
            ->assertSessionDoesntHaveErrors()
            ->assertExactJson([
                [
                    'title' => $org->title,
                    'path' => $org->path,
                    'changed' => $org->updated_at->toISOString(),
                    'field_city' => $org->city,
                    'field_event_service' => $org->service,
                    'field_events_api_key' => $org->service,
                    'field_focus_area' => $org->focus_area,
                    'field_homepage' => $org->uri,
                    'field_org_status' => $org->status->value,
                    'field_primary_contact_person' => $org->primary_contact_person,
                    'field_organization_type' => $org->organization_type,
                    'field_event_calendar_homepage' => $org->event_calendar_uri,
                    // 'field_regular_day_of_the_month' => '', // TBD
                    // 'field_regular_start_time' => '', // TBD
                    'field_year_established' => $org->established_at->year,
                    // 'field_year_inactive' => '', // TBD
                    'uuid' => $org->id,
                    'field_org_tags' => $org->tags->first()->id,
                ],
            ]);
    }
}
