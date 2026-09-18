<?php

namespace HackGreenville\Api\Resources\MapLayers\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MapLayerCollection extends ResourceCollection
{
    public $collects = MapLayerResource::class;
}
