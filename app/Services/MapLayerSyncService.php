<?php

namespace App\Services;

use App\Models\MapLayer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use RuntimeException;
use Throwable;

class MapLayerSyncService
{
    /**
     * Sync a single map layer's geojson file from its remote data source.
     *
     * @return array{success: bool, message: string}
     */
    public function sync(MapLayer $layer): array
    {
        try {
            $geojson = $this->fetchGeoJson($layer);
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        $path = 'geojson/' . basename($layer->slug) . '.geojson';

        Storage::disk('local')->put($path, json_encode($geojson, JSON_PRETTY_PRINT));

        $count = count($geojson['features'] ?? []);

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

    private function fetchGeoJson(MapLayer $layer): array
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

    private function fetchFromGeoJsonLink(string $url): array
    {
        $response = Http::timeout(30)->get($url);

        if ( ! $response->successful()) {
            throw new RuntimeException("Failed to fetch GeoJSON: HTTP {$response->status()}");
        }

        $body = $response->body();

        if (str_contains($response->header('Content-Type') ?? '', 'text/html') || str_starts_with(mb_trim($body), '<!DOCTYPE') || str_starts_with(mb_trim($body), '<html')) {
            throw new RuntimeException('GeoJSON endpoint returned HTML instead of JSON — the source may be unavailable.');
        }

        $data = $response->json();

        if ( ! isset($data['type']) || $data['type'] !== 'FeatureCollection') {
            throw new RuntimeException('Invalid GeoJSON: missing FeatureCollection type.');
        }

        return $data;
    }

    private function fetchFromCsvLink(string $url): array
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

            $properties = [];
            foreach ($record as $key => $value) {
                if (in_array($key, ['Latitude', 'Longitude'], true)) {
                    continue;
                }

                $propertyName = str_replace(' ', '_', mb_strtolower(mb_trim($key)));
                $properties[$propertyName] = mb_trim($value);
            }

            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float) $lng, (float) $lat],
                ],
                'properties' => $properties,
            ];
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }
}
