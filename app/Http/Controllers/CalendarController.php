<?php

namespace App\Http\Controllers;

use App\Contracts\CalendarContract;
use App\Http\Clients\GoogleCalendar;
use App\Models\Event;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class CalendarController extends Controller
{

    public function index(CalendarContract $calendar)
    {
        /** @var GoogleCalendar $calendar */

        $events = Event::getActive()->get();

        $events->each(function ($e) use ($calendar) {
            $calendar->addEvent($e->active_at, $e->active_at, $e->event_name, $e->description, false, ['location' => $e->venue . ""]);
        });

        $js = $calendar->js();
        $html  = $calendar->html();

        return view('testing', compact('js', 'html'));
//        dd($tst);
//        // Get the active events.
//        $events = Event::getActive()->get();
//
//        // Build out the calendar events to add to the calendar
//        $calendar_events = $events->map(function ($event) {
//            return Calendar::event(
//                $event->group_name . ' ' . $event->event_name, //event title
//                false, //full day event?
//                $event->localActiveAt, //start time (you can also use Carbon instead of DateTime)
//                $event->localActiveAt, //end time (you can also use Carbon instead of DateTime)
//                array_get($event, 'cache.uuid'), //optionally, you can specify an event ID
//                [
//                    'url' => $event->gCalUrl,
//                ]
//            );
//        });
//
//        // Build out the calendar and javascript.
//        $calendar = \Calendar::addEvents($calendar_events)
//            ->setOptions(['firstDay' => 1, /* set fullcalendar options */])
//            ->setCallbacks([
//                //set fullcalendar callback options (will not be JSON encoded)
//                'viewRender' => 'function() {console.log("Callbacks!");}',
//                'eventClick' => 'function(calEvent, jsEvent, view) {
//                    // do stuff alert(\'Event: \' + calEvent.title);
//                  }',
//            ]);
//
//        return view('calendar.index', compact('calendar'));
    }
}
