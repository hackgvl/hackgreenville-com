<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventsController extends Controller
{
    public function index()
    {
        // Collect unique months format Oct 2019 from the events.
        $events = Event::search()->get();

        $months = $events
                ->map(
                        function (Event $event) {
                            if (!$event->active_at) {
                                return false;
                            }

                            return $event->active_at->format('M Y');
                        }
                )
                ->filter(
                        function ($month) {
                            // Filter out months
                            return (bool)$month;
                        }
                )
                ->unique();

        return view('events.index', compact('events', 'months'));
    }
}
