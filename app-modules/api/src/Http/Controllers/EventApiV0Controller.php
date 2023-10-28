<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Models\Event;
use HackGreenville\Api\Resources\Events\V0\EventCollection;

class EventApiV0Controller
{
    public function __invoke()
    {
        return new EventCollection(
            resource: Event::query()->latest()->get()
        );
    }
}
