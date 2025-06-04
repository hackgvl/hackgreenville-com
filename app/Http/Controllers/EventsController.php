<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::future()
            ->published()
            ->with('organization', 'venue.state')
            ->orderBy('active_at')
            ->get();

        return Inertia::render('Events/Index', [
            'events' => $events,
        ]);
    }
}
