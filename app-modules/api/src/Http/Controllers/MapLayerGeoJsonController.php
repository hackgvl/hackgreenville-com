<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MapLayer;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MapLayerGeoJsonController extends Controller
{
    /**
     * Map Layer GeoJSON
     *
     * Returns the raw GeoJSON FeatureCollection for a specific map layer.
     *
     * The response is suitable for direct use with mapping libraries like Leaflet, Mapbox, or Google Maps.
     *
     * @urlParam mapLayer_slug string required The slug of the map layer. Example: breweries
     *
     * @response 200 scenario="Success" {"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Point","coordinates":[-82.398500,34.850700]},"properties":{"title":"Example Location"}}]}
     * @response 404 scenario="Not found" {"message":"GeoJSON data not found for this map layer."}
     */
    public function __invoke(MapLayer $mapLayer)
    {
        $path = "geojson/" . basename($mapLayer->slug) . ".geojson";

        if ( ! Storage::disk('local')->exists($path)) {
            abort(Response::HTTP_NOT_FOUND, 'GeoJSON data not found for this map layer.');
        }

        return response(Storage::disk('local')->get($path))
            ->header('Content-Type', 'application/geo+json');
    }
}
