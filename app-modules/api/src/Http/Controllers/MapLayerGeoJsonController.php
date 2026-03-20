<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MapLayer;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MapLayerGeoJsonController extends Controller
{
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
