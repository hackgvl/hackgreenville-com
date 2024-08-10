<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventsController extends Controller
{
    public function index()
    {
        $months = Event::getActive()
            ->published()
            ->with('organization', 'venue.state')
            ->orderBy('active_at')
            ->get()
            ->groupBy(fn (Event $event) => $event->active_at->format('F Y'));

        return view('events.index', compact('months'));
    }
}
