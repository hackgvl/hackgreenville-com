<?php

namespace Tests\Feature\Console\Commands;

use App\Models\MapLayer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\DatabaseTestCase;

class SyncMapLayersCommandTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_sync_all_layers(): void
    {
        MapLayer::factory()->create([
            'slug' => 'parks',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/parks.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response("Latitude,Longitude,Title\n34.85,-82.40,Central Park", 200),
        ]);

        $this->artisan('map:sync')
            ->assertSuccessful()
            ->expectsOutputToContain('parks');
    }

    public function test_sync_single_layer_by_slug(): void
    {
        MapLayer::factory()->create([
            'slug' => 'breweries',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/brew.csv',
        ]);

        MapLayer::factory()->create([
            'slug' => 'parks',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/parks.csv',
        ]);

        Http::fake([
            'example.com/brew.csv' => Http::response("Latitude,Longitude,Title\n34.85,-82.40,Test Brew", 200),
        ]);

        $this->artisan('map:sync', ['--slug' => 'breweries'])
            ->assertSuccessful();

        Storage::disk('local')->assertExists('geojson/breweries.geojson');
        Storage::disk('local')->assertMissing('geojson/parks.geojson');
    }

    public function test_sync_with_invalid_slug_fails(): void
    {
        $this->artisan('map:sync', ['--slug' => 'nonexistent'])
            ->assertFailed()
            ->expectsOutputToContain('not found');
    }

    public function test_sync_reports_failures(): void
    {
        MapLayer::factory()->create([
            'slug' => 'broken',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/broken.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response('Server Error', 500),
        ]);

        $this->artisan('map:sync')
            ->assertFailed();
    }
}
