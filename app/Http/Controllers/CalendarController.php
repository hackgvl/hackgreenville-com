<?php

namespace App\Http\Controllers;

use App\Contracts\CalendarContract;
use App\Http\Clients\GoogleCalendar;
use App\Models\Event;

class CalendarController extends Controller
{

    public function index(CalendarContract $calendar)
    {
        /** @var GoogleCalendar $calendar */

        $events = Event::getActive()->get();

        $events->each(function ($e) use ($calendar) {
            $calendar->addEvent($e->active_at, $e->expire_at, $e->event_name, $e->description, false, ['location' => $e->venue . '', 'event_id' => $e->id]);
        });

        $js = $calendar->js();
        $html  = $calendar->html();

        return view('calendar.index', compact('js', 'html'));
    }
}
