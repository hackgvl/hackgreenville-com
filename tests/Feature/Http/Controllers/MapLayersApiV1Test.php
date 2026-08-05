<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MapLayer;
use Illuminate\Support\Facades\Storage;
use Tests\DatabaseTestCase;

class MapLayersApiV1Test extends DatabaseTestCase
{
    public function test_index_returns_map_layers(): void
    {
        MapLayer::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.map-layers.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
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
            ]);
    }

    public function test_index_filters_by_title(): void
    {
        MapLayer::factory()->create(['title' => 'Breweries']);
        MapLayer::factory()->create(['title' => 'Dog Parks']);

        $response = $this->getJson(route('api.v1.map-layers.index', ['title' => 'Brew']));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Breweries');
    }

    public function test_index_sorts_by_field(): void
    {
        MapLayer::factory()->create(['title' => 'Zebras']);
        MapLayer::factory()->create(['title' => 'Apples']);

        $response = $this->getJson(route('api.v1.map-layers.index', [
            'sort_by' => 'title',
            'sort_direction' => 'asc',
        ]));

        $response->assertOk()
            ->assertJsonPath('data.0.title', 'Apples')
            ->assertJsonPath('data.1.title', 'Zebras');
    }

    public function test_index_defaults_to_title_asc_sort(): void
    {
        MapLayer::factory()->create(['title' => 'Zebras']);
        MapLayer::factory()->create(['title' => 'Apples']);

        $response = $this->getJson(route('api.v1.map-layers.index'));

        $response->assertOk()
            ->assertJsonPath('data.0.title', 'Apples');
    }

    public function test_index_paginates_results(): void
    {
        MapLayer::factory()->count(20)->create();

        $response = $this->getJson(route('api.v1.map-layers.index', ['per_page' => 5]));

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_index_validates_per_page_max(): void
    {
        $response = $this->getJson(route('api.v1.map-layers.index', ['per_page' => 200]));

        $response->assertUnprocessable();
    }

    public function test_index_validates_sort_by_field(): void
    {
        $response = $this->getJson(route('api.v1.map-layers.index', ['sort_by' => 'invalid_field']));

        $response->assertUnprocessable();
    }

    public function test_geojson_returns_file_contents(): void
    {
        Storage::fake('local');

        $mapLayer = MapLayer::factory()->create(['slug' => 'test-layer']);
        Storage::disk('local')->put('geojson/test-layer.geojson', '{"type":"FeatureCollection","features":[]}');

        $response = $this->get(route('api.v1.map-layers.geojson', ['mapLayer' => 'test-layer']));

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/geo+json');

        $this->assertJsonStringEqualsJsonString(
            '{"type":"FeatureCollection","features":[]}',
            $response->getContent()
        );
    }

    public function test_geojson_returns_404_when_file_missing(): void
    {
        Storage::fake('local');

        MapLayer::factory()->create(['slug' => 'missing-layer']);

        $response = $this->get(route('api.v1.map-layers.geojson', ['mapLayer' => 'missing-layer']));

        $response->assertNotFound();
    }

    public function test_geojson_returns_404_for_nonexistent_layer(): void
    {
        $response = $this->get(route('api.v1.map-layers.geojson', ['mapLayer' => 'does-not-exist']));

        $response->assertNotFound();
    }

    public function test_index_sorts_descending(): void
    {
        MapLayer::factory()->create(['title' => 'Apples']);
        MapLayer::factory()->create(['title' => 'Zebras']);

        $response = $this->getJson(route('api.v1.map-layers.index', [
            'sort_by' => 'title',
            'sort_direction' => 'desc',
        ]));

        $response->assertOk()
            ->assertJsonPath('data.0.title', 'Zebras')
            ->assertJsonPath('data.1.title', 'Apples');
    }

    public function test_index_returns_empty_when_no_matches(): void
    {
        MapLayer::factory()->create(['title' => 'Breweries']);

        $response = $this->getJson(route('api.v1.map-layers.index', ['title' => 'Nonexistent']));

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_title_filter_escapes_like_wildcards(): void
    {
        MapLayer::factory()->create(['title' => '100% Complete']);
        MapLayer::factory()->create(['title' => '1001 Things']);

        $response = $this->getJson(route('api.v1.map-layers.index', ['title' => '100%']));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', '100% Complete');
    }

    public function test_title_filter_escapes_underscore_wildcard(): void
    {
        MapLayer::factory()->create(['title' => 'A_B Special']);
        MapLayer::factory()->create(['title' => 'AXB Other']);

        $response = $this->getJson(route('api.v1.map-layers.index', ['title' => 'A_B']));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'A_B Special');
    }

    public function test_geojson_rejects_path_traversal_slugs(): void
    {
        $response = $this->get(route('api.v1.map-layers.geojson', ['mapLayer' => '../etc/passwd']));

        $response->assertNotFound();
    }
}
