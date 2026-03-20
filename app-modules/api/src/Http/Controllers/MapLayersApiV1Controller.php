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
     * This API provides access to community-curated map layer data.
     *
     * @apiResource HackGreenville\Api\Resources\MapLayers\V1\MapLayerCollection
     * @apiResourceModel App\Models\MapLayer
     */
    public function __invoke(MapLayersApiV1Request $request)
    {
        $query = MapLayer::query()
            ->when($request->filled('title'), function (Builder $query) use ($request) {
                $title = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $request->input('title'));
                $query->whereRaw("title LIKE ? ESCAPE '!'", ['%' . $title . '%']);
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
