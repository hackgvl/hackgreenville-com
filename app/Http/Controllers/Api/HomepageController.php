<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
//use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function events()
    {
        // we only need a few events for the homepage
        return Event::getActive()
            ->take(5)
            ->select(['event_name', 'description', 'active_at', 'expire_at'])
            ->get();
    }
}
