<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

//use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * collect resources for the homepage
     * @return AnonymousResourceCollection
     */
    public function events()
    {
        // we only need a few events for the homepage
        $events = Event::getActive()
                ->take(5)
                ->select(['event_name', 'group_name', 'description', 'active_at', 'expire_at', 'uri', 'cancelled_at'])
                ->get();

        return EventResource::collection($events);
    }
}
