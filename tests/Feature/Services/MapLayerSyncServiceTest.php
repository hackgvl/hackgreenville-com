<?php

namespace Tests\Feature\Services;

use App\Models\MapLayer;
use App\Services\MapLayerSyncService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\DatabaseTestCase;

class MapLayerSyncServiceTest extends DatabaseTestCase
{
    private MapLayerSyncService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(MapLayerSyncService::class);
        Storage::fake('local');
    }

    public function test_sync_from_csv_creates_geojson_file(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'breweries',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response(
                "Latitude,Longitude,Title,Website\n34.85,-82.40,Test Brewery,https://example.com\n34.86,-82.41,Another Brewery,https://other.com",
                200
            ),
        ]);

        $result = $this->service->sync($layer);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('2 features', $result['message']);

        Storage::disk('local')->assertExists('geojson/breweries.geojson');

        $geojson = json_decode(Storage::disk('local')->get('geojson/breweries.geojson'), true);

        $this->assertEquals('FeatureCollection', $geojson['type']);
        $this->assertCount(2, $geojson['features']);
        $this->assertEquals('Test Brewery', $geojson['features'][0]['properties']['title']);
        $this->assertEquals([-82.40, 34.85], $geojson['features'][0]['geometry']['coordinates']);
        $this->assertEquals('https://example.com', $geojson['features'][0]['properties']['website']);
    }

    public function test_sync_from_csv_skips_rows_without_coordinates(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'test-layer',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response(
                "Latitude,Longitude,Title\n34.85,-82.40,Has Coords\n,,,Empty Row\n34.86,-82.41,Also Has Coords",
                200
            ),
        ]);

        $result = $this->service->sync($layer);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('2 features', $result['message']);
    }

    public function test_sync_from_geojson_link(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'coworking',
            'geojson_link' => 'https://example.com/points.geojson',
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        $geojsonData = [
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => ['type' => 'Point', 'coordinates' => [-82.40, 34.85]],
                    'properties' => ['title' => 'Test Space'],
                ],
            ],
        ];

        Http::fake([
            'example.com/points.geojson' => Http::response($geojsonData, 200),
        ]);

        $result = $this->service->sync($layer);

        $this->assertTrue($result['success']);
        Storage::disk('local')->assertExists('geojson/coworking.geojson');

        $stored = json_decode(Storage::disk('local')->get('geojson/coworking.geojson'), true);
        $this->assertEquals('Test Space', $stored['features'][0]['properties']['title']);
    }

    public function test_sync_prefers_geojson_link_over_raw_data_link(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'test',
            'geojson_link' => 'https://example.com/points.geojson',
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/points.geojson' => Http::response([
                'type' => 'FeatureCollection',
                'features' => [],
            ], 200),
            'example.com/data.csv' => Http::response("Latitude,Longitude,Title\n34.85,-82.40,From CSV", 200),
        ]);

        $this->service->sync($layer);

        // The CSV endpoint should not have been called
        Http::assertSentCount(1);
    }

    public function test_sync_fails_when_no_data_source(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'empty',
            'geojson_link' => null,
            'raw_data_link' => null,
        ]);

        $result = $this->service->sync($layer);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('No data source', $result['message']);
    }

    public function test_sync_fails_on_http_error(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'broken',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/missing.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response('Not Found', 404),
        ]);

        $result = $this->service->sync($layer);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('404', $result['message']);
    }

    public function test_sync_fails_on_invalid_geojson(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'bad-geojson',
            'geojson_link' => 'https://example.com/bad.geojson',
        ]);

        Http::fake([
            'example.com/*' => Http::response(['type' => 'InvalidType'], 200),
        ]);

        $result = $this->service->sync($layer);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid GeoJSON', $result['message']);
    }

    public function test_sync_all_returns_results_for_each_layer(): void
    {
        MapLayer::factory()->create([
            'slug' => 'layer-a',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/a.csv',
        ]);

        MapLayer::factory()->create([
            'slug' => 'layer-b',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/b.csv',
        ]);

        Http::fake([
            'example.com/a.csv' => Http::response("Latitude,Longitude,Title\n34.85,-82.40,Place A", 200),
            'example.com/b.csv' => Http::response("Latitude,Longitude,Title\n34.86,-82.41,Place B", 200),
        ]);

        $results = $this->service->syncAll();

        $this->assertCount(2, $results);
        $this->assertTrue($results['layer-a']['success']);
        $this->assertTrue($results['layer-b']['success']);
    }

    public function test_sync_escapes_forward_slashes_in_json_output(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'slash-test',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response(
                "Latitude,Longitude,Title,Website\n34.85,-82.40,Test Place,https://example.com/path/to/page",
                200
            ),
        ]);

        $this->service->sync($layer);

        $raw = Storage::disk('local')->get('geojson/slash-test.geojson');

        $this->assertStringContainsString('https:\/\/example.com\/path\/to\/page', $raw);
        $this->assertStringNotContainsString('https://example.com/path/to/page', $raw);
    }

    public function test_sync_overwrites_existing_geojson_file(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'overwrite-test',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Storage::disk('local')->put('geojson/overwrite-test.geojson', json_encode([
            'type' => 'FeatureCollection',
            'features' => [
                ['type' => 'Feature', 'geometry' => ['type' => 'Point', 'coordinates' => [0, 0]], 'properties' => ['title' => 'Old Data']],
            ],
        ]));

        Http::fake([
            'example.com/*' => Http::response(
                "Latitude,Longitude,Title\n34.85,-82.40,New Data",
                200
            ),
        ]);

        $this->service->sync($layer);

        $geojson = json_decode(Storage::disk('local')->get('geojson/overwrite-test.geojson'), true);

        $this->assertCount(1, $geojson['features']);
        $this->assertEquals('New Data', $geojson['features'][0]['properties']['title']);
    }

    public function test_sync_produces_empty_features_when_csv_has_no_valid_rows(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'empty-csv',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response(
                "Latitude,Longitude,Title\n,,,No Coords\nabc,def,Bad Coords",
                200
            ),
        ]);

        $result = $this->service->sync($layer);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('0 features', $result['message']);

        $geojson = json_decode(Storage::disk('local')->get('geojson/empty-csv.geojson'), true);
        $this->assertEquals('FeatureCollection', $geojson['type']);
        $this->assertCount(0, $geojson['features']);
    }

    public function test_sync_fails_when_csv_endpoint_returns_html_error_page(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'dead-sheet',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response(
                '<!DOCTYPE html><html><body>Sorry, the file you have requested does not exist.</body></html>',
                200,
                ['Content-Type' => 'text/html']
            ),
        ]);

        $result = $this->service->sync($layer);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('HTML instead of CSV', $result['message']);
        Storage::disk('local')->assertMissing('geojson/dead-sheet.geojson');
    }

    public function test_sync_fails_when_geojson_endpoint_returns_html_error_page(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'dead-geojson',
            'geojson_link' => 'https://example.com/points.geojson',
        ]);

        Http::fake([
            'example.com/*' => Http::response(
                '<!DOCTYPE html><html><body>Not Found</body></html>',
                200,
                ['Content-Type' => 'text/html']
            ),
        ]);

        $result = $this->service->sync($layer);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('HTML instead of JSON', $result['message']);
        Storage::disk('local')->assertMissing('geojson/dead-geojson.geojson');
    }

    public function test_sync_detects_html_by_body_even_without_content_type_header(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'sneaky-html',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response(
                '<!DOCTYPE html><html><body>Error</body></html>',
                200
            ),
        ]);

        $result = $this->service->sync($layer);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('HTML instead of CSV', $result['message']);
    }

    public function test_sync_output_is_pretty_printed(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'pretty-test',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response("Latitude,Longitude,Title\n34.85,-82.40,Test", 200),
        ]);

        $this->service->sync($layer);

        $raw = Storage::disk('local')->get('geojson/pretty-test.geojson');

        // Pretty-printed JSON has newlines and indentation
        $this->assertStringContainsString("\n", $raw);
        $this->assertStringContainsString('    ', $raw);
    }

    public function test_sync_uses_slug_for_filename(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'my-custom-layer',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response("Latitude,Longitude,Title\n34.85,-82.40,Test", 200),
        ]);

        $this->service->sync($layer);

        Storage::disk('local')->assertExists('geojson/my-custom-layer.geojson');
    }

    public function test_sync_excludes_latitude_and_longitude_from_properties(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'props-test',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response("Latitude,Longitude,Title,Notes\n34.85,-82.40,Place,Some notes", 200),
        ]);

        $this->service->sync($layer);

        $geojson = json_decode(Storage::disk('local')->get('geojson/props-test.geojson'), true);
        $properties = $geojson['features'][0]['properties'];

        $this->assertArrayNotHasKey('latitude', $properties);
        $this->assertArrayNotHasKey('longitude', $properties);
        $this->assertArrayHasKey('title', $properties);
        $this->assertArrayHasKey('notes', $properties);
    }

    public function test_csv_column_headers_are_normalized_to_snake_case(): void
    {
        $layer = MapLayer::factory()->create([
            'slug' => 'test-normalize',
            'geojson_link' => null,
            'raw_data_link' => 'https://example.com/data.csv',
        ]);

        Http::fake([
            'example.com/*' => Http::response(
                "Latitude,Longitude,Title,Dog Friendly,Food On Premises\n34.85,-82.40,Test Place,Yes,No",
                200
            ),
        ]);

        $this->service->sync($layer);

        $geojson = json_decode(Storage::disk('local')->get('geojson/test-normalize.geojson'), true);
        $properties = $geojson['features'][0]['properties'];

        $this->assertArrayHasKey('dog_friendly', $properties);
        $this->assertArrayHasKey('food_on_premises', $properties);
        $this->assertEquals('Yes', $properties['dog_friendly']);
    }
}
