<?php

namespace HackGreenville\Api\Resources\MapLayers\V1;

use App\Models\MapLayer;
use HackGreenville\Api\Resources\ApiResource;
use Illuminate\Http\Request;

class MapLayerResource extends ApiResource
{
    /** @var MapLayer $resource */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId($this->resource),
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'center_latitude' => (float) $this->resource->center_latitude,
            'center_longitude' => (float) $this->resource->center_longitude,
            'zoom_level' => $this->resource->zoom_level,
            'geojson_link' => $this->resource->geojson_link,
            'geojson_url' => route('api.v1.map-layers.geojson', ['mapLayer' => $this->resource->slug]),
            'contribute_link' => $this->resource->contribute_link,
            'raw_data_link' => $this->resource->raw_data_link,
            'maintainers' => $this->resource->maintainers ?? [],
            'created_at' => $this->resource->created_at->toISOString(),
            'updated_at' => $this->resource->updated_at->toISOString(),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
                'timestamp' => $this->getTime(),
            ],
        ];
    }
}
