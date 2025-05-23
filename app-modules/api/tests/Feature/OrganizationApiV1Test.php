<?php

namespace HackGreenville\Api\Tests\Feature;

use App\Models\Org;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationApiV1Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_organizations_list()
    {
        $org = Org::factory()->create();
        $tag = Tag::factory()->create();
        $org->tags()->attach($tag);

        $response = $this->getJson('/api/v1/organizations');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'path',
                    'city',
                    'service',
                    'service_api_key',
                    'focus_area',
                    'website_url',
                    'event_calendar_url',
                    'primary_contact',
                    'status',
                    'organization_type',
                    'established_year',
                    'tags',
                    'created_at',
                    'updated_at',
                ]
            ],
            'links',
            'meta',
        ]);

        $response->assertJsonPath('data.0.id', $org->id);
        $response->assertJsonPath('data.0.tags.0.id', $tag->id);
    }

    public function test_can_filter_organizations_by_tags()
    {
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        $org1 = Org::factory()->create(['title' => 'Org with Tag 1']);
        $org1->tags()->attach($tag1);

        $org2 = Org::factory()->create(['title' => 'Org with Tag 2']);
        $org2->tags()->attach($tag2);

        $response = $this->getJson('/api/v1/organizations?tags[]=' . $tag1->id);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $org1->id);
        $response->assertJsonPath('data.0.title', 'Org with Tag 1');
    }

    public function test_can_filter_organizations_by_title()
    {
        Org::factory()->create(['title' => 'First Organization']);
        Org::factory()->create(['title' => 'Second Organization']);
        Org::factory()->create(['title' => 'Different Name']);

        $response = $this->getJson('/api/v1/organizations?title=Organization');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function test_can_paginate_organizations()
    {
        Org::factory()->count(20)->create();

        $response = $this->getJson('/api/v1/organizations?per_page=10');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonPath('meta.per_page', 10);
        $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_can_sort_organizations()
    {
        Org::factory()->create(['title' => 'Z Organization']);
        Org::factory()->create(['title' => 'A Organization']);

        $response = $this->getJson('/api/v1/organizations?sort_by=title&sort_direction=asc');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.title', 'A Organization');

        $response = $this->getJson('/api/v1/organizations?sort_by=title&sort_direction=desc');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.title', 'Z Organization');
    }
}
