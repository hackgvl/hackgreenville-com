<?php

namespace App\Http\Controllers;

use App\Models\Event;
use DB;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::where('active_date', '>', DB::raw('UTC_DATE'))->get();

        return view('events.index', compact('events'));
    }
}
