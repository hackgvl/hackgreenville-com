<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MapLayer;
use Tests\DatabaseTestCase;

class MapLayersControllerTest extends DatabaseTestCase
{
    public function test_map_layers_page_loads(): void
    {
        MapLayer::factory()->count(3)->create();

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertViewIs('map-layers.index');
        $response->assertViewHas('layers');
    }

    public function test_map_layers_page_displays_layer_titles(): void
    {
        MapLayer::factory()->create(['title' => 'Breweries']);
        MapLayer::factory()->create(['title' => 'Dog Parks']);

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSeeText('Breweries');
        $response->assertSeeText('Dog Parks');
    }

    public function test_map_layers_are_sorted_alphabetically(): void
    {
        MapLayer::factory()->create(['title' => 'Waterfalls']);
        MapLayer::factory()->create(['title' => 'Art Galleries']);
        MapLayer::factory()->create(['title' => 'Libraries']);

        $response = $this->get(route('map-layers.index'));

        $layers = $response->viewData('layers');

        $this->assertEquals('Art Galleries', $layers[0]->title);
        $this->assertEquals('Libraries', $layers[1]->title);
        $this->assertEquals('Waterfalls', $layers[2]->title);
    }

    public function test_map_layers_page_shows_contribute_links(): void
    {
        MapLayer::factory()->create([
            'title' => 'Breweries',
            'contribute_link' => 'https://docs.google.com/spreadsheets/d/example/edit',
        ]);

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSee('https://docs.google.com/spreadsheets/d/example/edit', false);
        $response->assertSeeText('Edit Spreadsheet');
    }

    public function test_map_layers_page_shows_geojson_links(): void
    {
        $layer = MapLayer::factory()->create(['slug' => 'breweries']);

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSee(route('api.v1.map-layers.geojson', $layer), false);
        $response->assertSeeText('GeoJSON');
    }

    public function test_map_layers_page_shows_csv_links(): void
    {
        MapLayer::factory()->create([
            'raw_data_link' => 'https://docs.google.com/spreadsheets/d/e/example/pub?output=csv',
        ]);

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSeeText('CSV');
    }

    public function test_map_layers_page_hides_contribute_link_when_null(): void
    {
        MapLayer::factory()->create([
            'contribute_link' => null,
            'raw_data_link' => null,
        ]);

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        // The layer card should not contain a spreadsheet edit link
        $response->assertDontSee('Edit Spreadsheet</a>', false);
    }

    public function test_map_layers_page_hides_csv_link_when_null(): void
    {
        MapLayer::factory()->create([
            'raw_data_link' => null,
            'contribute_link' => null,
        ]);

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSeeText('GeoJSON');
        $response->assertDontSee('CSV</a>', false);
    }

    public function test_map_layers_page_shows_descriptions(): void
    {
        MapLayer::factory()->create([
            'title' => 'Breweries',
            'description' => 'A community-curated map of local breweries.',
        ]);

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSeeText('A community-curated map of local breweries.');
    }

    public function test_map_layers_page_shows_maintainers(): void
    {
        MapLayer::factory()->create([
            'maintainers' => [
                ['name' => 'Alice Smith'],
                ['name' => 'Bob Jones'],
            ],
        ]);

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSeeText('Alice Smith');
        $response->assertSeeText('Bob Jones');
    }

    public function test_map_layers_page_shows_layer_count(): void
    {
        MapLayer::factory()->count(5)->create();

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSeeText('5');
    }

    public function test_map_layers_page_renders_with_no_layers(): void
    {
        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSeeText('Open Map Data');
        $response->assertViewHas('layers', function ($layers) {
            return $layers->isEmpty();
        });
    }

    public function test_map_layers_page_excludes_soft_deleted_layers(): void
    {
        MapLayer::factory()->create(['title' => 'Active Layer']);
        MapLayer::factory()->create(['title' => 'Deleted Layer', 'deleted_at' => now()]);

        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSeeText('Active Layer');
        $response->assertDontSeeText('Deleted Layer');
    }

    public function test_map_layers_page_has_api_docs_link(): void
    {
        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSee('/docs/api', false);
        $response->assertSeeText('API Docs');
    }

    public function test_map_layers_page_has_demo_link(): void
    {
        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSee('https://hackgvl.github.io/open-map-data-multi-layers-demo/', false);
        $response->assertSeeText('Multi-Layer Demo');
    }

    public function test_map_layers_page_has_correct_meta(): void
    {
        $response = $this->get(route('map-layers.index'));

        $response->assertOk();
        $response->assertSee('Open Map Data', false);
        $response->assertSee('Community-curated', false);
    }
}
