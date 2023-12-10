<?php

namespace HackGreenville\Api\Resources\Events\V0;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * This exists merely to handle legacy events api.
 */
class EventCollection extends ResourceCollection
{
    public static $wrap = null;

    public $collects = EventResource::class;

    public function toArray(Request $request): array
    {
        return $this->collection->toArray();
    }
}
