<?php

namespace App\Http\Controllers;

use App\Contracts\CalendarContract;
use App\Http\Clients\GoogleCalendar;
use App\Models\Event;

//use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class CalendarController extends Controller
{

    public function index(CalendarContract $calendar)
    {
        /** @var GoogleCalendar $calendar */

        $events = Event::includePast('2 months')->get();

        $events->each(function ($e) use ($calendar) {
            $attributes = [
                'location'  => $e->venue . '',
                'event_id'  => $e->id,
                'event_url' => $e->uri,
            ];

            $calendar->addEvent($e->active_at, $e->expire_at, $e->event_name, $e->description, false, $attributes);
        });

        $js = $calendar->js();
        $html  = $calendar->html();

        return view('calendar.index', compact('js', 'html'));
    }
}
