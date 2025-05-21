<?php

namespace HackGreenville\Api\Resources\Events\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventCollection extends ResourceCollection
{
    public $collects = EventResource::class;
}
