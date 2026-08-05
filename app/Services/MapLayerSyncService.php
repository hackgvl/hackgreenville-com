<?php

namespace App\Services;

use App\Models\MapLayer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JsonException;
use League\Csv\Reader;
use RuntimeException;
use stdClass;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;
use Throwable;

class MapLayerSyncService
{
    private static ?Schema $geoJsonSchema = null;

    /**
     * Sync a single map layer's geojson file from its remote data source.
     *
     * @return array{success: bool, message: string}
     */
    public function sync(MapLayer $layer): array
    {
        try {
            $geojson = $this->fetchGeoJson($layer);
            $this->assertValidGeoJson($geojson);
            // JSON_THROW_ON_ERROR guards against writing a broken file if the
            // structure ever contains something json_encode can't serialize.
            $encoded = json_encode($geojson, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return ['success' => false, 'message' => "Failed to encode GeoJSON: {$e->getMessage()}"];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        $path = 'geojson/' . basename($layer->slug) . '.geojson';

        Storage::disk('local')->put($path, $encoded);

        $count = count($geojson->features ?? []);

        return ['success' => true, 'message' => "Synced {$count} features."];
    }

    /**
     * Sync all map layers. Returns a summary of results.
     *
     * @return array<string, array{success: bool, message: string}>
     */
    public function syncAll(): array
    {
        $results = [];

        MapLayer::query()->each(function (MapLayer $layer) use (&$results) {
            $results[$layer->slug] = $this->sync($layer);
        });

        return $results;
    }

    private function fetchGeoJson(MapLayer $layer): stdClass
    {
        // Prefer geojson_link if available (already in geojson format)
        if ($layer->geojson_link) {
            return $this->fetchFromGeoJsonLink($layer->geojson_link);
        }

        // Fall back to raw_data_link (CSV from Google Sheets)
        if ($layer->raw_data_link) {
            return $this->fetchFromCsvLink($layer->raw_data_link);
        }

        throw new RuntimeException("No data source configured (needs geojson_link or raw_data_link).");
    }

    private function fetchFromGeoJsonLink(string $url): stdClass
    {
        $response = Http::timeout(30)->get($url);

        if ( ! $response->successful()) {
            throw new RuntimeException("Failed to fetch GeoJSON: HTTP {$response->status()}");
        }

        $body = $response->body();

        if (str_contains($response->header('Content-Type') ?? '', 'text/html') || str_starts_with(mb_trim($body), '<!DOCTYPE') || str_starts_with(mb_trim($body), '<html')) {
            throw new RuntimeException('GeoJSON endpoint returned HTML instead of JSON — the source may be unavailable.');
        }

        // Decode to objects (not associative arrays) so empty JSON objects such
        // as `"properties": {}` survive as objects rather than collapsing to `[]`,
        // which would fail structural validation and corrupt the stored file.
        $data = json_decode($body);

        if ( ! $data instanceof stdClass) {
            throw new RuntimeException('Invalid GeoJSON: response was not a JSON object.');
        }

        return $data;
    }

    private function fetchFromCsvLink(string $url): stdClass
    {
        $response = Http::timeout(30)->get($url);

        if ( ! $response->successful()) {
            throw new RuntimeException("Failed to fetch CSV: HTTP {$response->status()}");
        }

        $body = $response->body();
        $contentType = $response->header('Content-Type') ?? '';

        // Google Sheets returns HTML error pages with 200 status when a sheet is deleted/unpublished
        if (str_contains($contentType, 'text/html') || str_starts_with(mb_trim($body), '<!DOCTYPE') || str_starts_with(mb_trim($body), '<html')) {
            throw new RuntimeException('CSV endpoint returned HTML instead of CSV data — the source may be unavailable.');
        }

        $csv = Reader::createFromString($body);
        $csv->setHeaderOffset(0);

        $features = [];

        foreach ($csv->getRecords() as $record) {
            $lat = mb_trim($record['Latitude'] ?? '');
            $lng = mb_trim($record['Longitude'] ?? '');

            // Skip rows without valid coordinates
            if ($lat === '' || $lng === '' || ! is_numeric($lat) || ! is_numeric($lng)) {
                continue;
            }

            $lat = (float) $lat;
            $lng = (float) $lng;

            // Skip rows whose coordinates fall outside valid lat/lng ranges,
            // which usually means a typo or swapped columns in the spreadsheet.
            if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                continue;
            }

            $properties = new stdClass();
            foreach ($record as $key => $value) {
                if (in_array($key, ['Latitude', 'Longitude'], true)) {
                    continue;
                }

                $propertyName = str_replace(' ', '_', mb_strtolower(mb_trim($key)));
                $properties->{$propertyName} = mb_trim($value);
            }

            $features[] = (object) [
                'type' => 'Feature',
                'geometry' => (object) [
                    'type' => 'Point',
                    'coordinates' => [$lng, $lat],
                ],
                'properties' => $properties,
            ];
        }

        return (object) [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }

    /**
     * Validate the assembled GeoJSON against the bundled RFC 7946 schema.
     *
     * @throws RuntimeException when the structure is not valid GeoJSON
     */
    private function assertValidGeoJson(stdClass $geojson): void
    {
        try {
            $this->geoJsonSchema()->in($geojson);
        } catch (InvalidValue $e) {
            throw new RuntimeException('Invalid GeoJSON: ' . $e->getMessage());
        }
    }

    private function geoJsonSchema(): Schema
    {
        if (self::$geoJsonSchema === null) {
            $definition = json_decode(file_get_contents(__DIR__ . '/geojson-schema.json'));
            self::$geoJsonSchema = Schema::import($definition);
        }

        return self::$geoJsonSchema;
    }
}
