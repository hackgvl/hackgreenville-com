<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MapLayer;
use HackGreenville\Api\Http\Requests\MapLayersApiV1Request;
use HackGreenville\Api\Resources\MapLayers\V1\MapLayerCollection;
use Illuminate\Database\Eloquent\Builder;

class MapLayersApiV1Controller extends Controller
{
    /**
     * Map Layers API v1
     *
     * This API provides access to community-curated map layer data for the Greenville, SC area.
     *
     * Each map layer represents a collection of geographic features (e.g. breweries, parks, trails)
     * sourced from community-maintained Google Spreadsheets and served as GeoJSON.
     *
     * @apiResource HackGreenville\Api\Resources\MapLayers\V1\MapLayerCollection
     * @apiResourceModel App\Models\MapLayer states=forDocumentation
     *
     * @responseField data[].geojson_link string The remote/origin source URL the layer's GeoJSON is synced from (may be null when the layer is built from a spreadsheet). This is the upstream source, not the HackGreenville-hosted file.
     * @responseField data[].geojson_url string The HackGreenville-hosted endpoint that serves this layer's GeoJSON FeatureCollection. Consumers should read GeoJSON from here, not from geojson_link.
     * @responseField data[].raw_data_link string The remote spreadsheet (CSV) the GeoJSON is generated from when no geojson_link is set.
     */
    public function __invoke(MapLayersApiV1Request $request)
    {
        $query = MapLayer::query()
            ->when($request->filled('slug'), function (Builder $query) use ($request) {
                $query->where('slug', $request->input('slug'));
            })
            ->when($request->filled('title'), function (Builder $query) use ($request) {
                $query->whereLikeContains('title', $request->input('title'));
            })
            ->when($request->filled('sort_by'), function (Builder $query) use ($request) {
                $sortDirection = $request->input('sort_direction') === 'desc' ? 'desc' : 'asc';
                $query->orderBy($request->input('sort_by'), $sortDirection);
            }, function (Builder $query) {
                $query->orderBy('title', 'asc');
            });

        $perPage = $request->input('per_page', 15);
        $mapLayers = $query->paginate($perPage);

        return new MapLayerCollection($mapLayers);
    }
}
