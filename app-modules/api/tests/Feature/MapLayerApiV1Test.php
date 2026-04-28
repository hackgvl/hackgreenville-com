<?php

namespace HackGreenville\Api\Tests\Feature;

use App\Models\MapLayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MapLayerApiV1Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_map_layers_list(): void
    {
        $layer = MapLayer::factory()->create();

        $response = $this->getJson('/api/v1/map-layers');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'description',
                    'center_latitude',
                    'center_longitude',
                    'zoom_level',
                    'geojson_link',
                    'geojson_url',
                    'contribute_link',
                    'raw_data_link',
                    'maintainers',
                    'created_at',
                    'updated_at',
                ],
            ],
            'links',
            'meta',
        ]);

        $response->assertJsonPath('data.0.title', $layer->title);
        $response->assertJsonPath('data.0.slug', $layer->slug);
    }

    public function test_can_paginate_map_layers(): void
    {
        MapLayer::factory()->count(20)->create();

        $response = $this->getJson('/api/v1/map-layers?per_page=10');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonPath('meta.per_page', 10);
        $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_can_filter_map_layers_by_title(): void
    {
        MapLayer::factory()->create(['title' => 'Breweries']);
        MapLayer::factory()->create(['title' => 'Dog Parks']);
        MapLayer::factory()->create(['title' => 'Craft Breweries']);

        $response = $this->getJson('/api/v1/map-layers?title=Breweries');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function test_can_sort_map_layers(): void
    {
        MapLayer::factory()->create(['title' => 'Waterfalls']);
        MapLayer::factory()->create(['title' => 'Art Galleries']);

        $response = $this->getJson('/api/v1/map-layers?sort_by=title&sort_direction=asc');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.title', 'Art Galleries');

        $response = $this->getJson('/api/v1/map-layers?sort_by=title&sort_direction=desc');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.title', 'Waterfalls');
    }

    public function test_default_sort_is_title_asc(): void
    {
        MapLayer::factory()->create(['title' => 'Waterfalls']);
        MapLayer::factory()->create(['title' => 'Art Galleries']);
        MapLayer::factory()->create(['title' => 'Libraries']);

        $response = $this->getJson('/api/v1/map-layers');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.title', 'Art Galleries');
        $response->assertJsonPath('data.1.title', 'Libraries');
        $response->assertJsonPath('data.2.title', 'Waterfalls');
    }

    public function test_rejects_invalid_sort_by_field(): void
    {
        $response = $this->getJson('/api/v1/map-layers?sort_by=description');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sort_by');
    }

    public function test_excludes_soft_deleted_map_layers(): void
    {
        MapLayer::factory()->create(['title' => 'Active Layer']);
        MapLayer::factory()->create(['title' => 'Deleted Layer', 'deleted_at' => now()]);

        $response = $this->getJson('/api/v1/map-layers');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.title', 'Active Layer');
    }

    public function test_empty_list_returns_valid_structure(): void
    {
        $response = $this->getJson('/api/v1/map-layers');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_geojson_url_is_present_in_response(): void
    {
        $layer = MapLayer::factory()->create(['slug' => 'breweries']);

        $response = $this->getJson('/api/v1/map-layers');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.geojson_url', fn ($url) => str_contains($url, '/api/v1/map-layers/breweries/geojson'));
    }

    public function test_geojson_endpoint_returns_geojson(): void
    {
        $layer = MapLayer::factory()->create(['slug' => 'breweries']);

        $geojson = json_encode([
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => ['type' => 'Point', 'coordinates' => [-82.398500, 34.850700]],
                    'properties' => ['title' => 'Test Brewery'],
                ],
            ],
        ]);

        Storage::fake('local');
        Storage::disk('local')->put('geojson/breweries.geojson', $geojson);

        $response = $this->get('/api/v1/map-layers/breweries/geojson');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/geo+json');
        $response->assertJson([
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'properties' => ['title' => 'Test Brewery'],
                ],
            ],
        ]);
    }

    public function test_geojson_endpoint_returns_404_when_file_missing(): void
    {
        Storage::fake('local');

        $layer = MapLayer::factory()->create(['slug' => 'nonexistent']);

        $response = $this->getJson('/api/v1/map-layers/nonexistent/geojson');

        $response->assertStatus(404);
    }

    public function test_geojson_endpoint_returns_404_for_unknown_slug(): void
    {
        $response = $this->getJson('/api/v1/map-layers/does-not-exist/geojson');

        $response->assertStatus(404);
    }
}
