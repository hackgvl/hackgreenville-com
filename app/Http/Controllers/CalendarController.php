<?php

namespace App\Http\Controllers;

use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class CalendarController extends Controller
{

    public function show()
    {
        $events = [];

        $list = getEvents();

//        created_at
//        time
//        event_name
//        url
//        uuid
//        group_name

        foreach ($list as $event) {

            $google_url = build_cal_url($event);

            $new_event = Calendar::event(
                $event->group_name . ' ' . $event->event_name, //event title
                false, //full day event?
                $event->localtime, //start time (you can also use Carbon instead of DateTime)
                $event->localtime, //end time (you can also use Carbon instead of DateTime)
                $event->uuid, //optionally, you can specify an event ID
                [
                    'url' => $google_url,
                ]
            );

            $events[] = $new_event;
        }

        $calendar = \Calendar::addEvents($events) //add an array with addEvents
        ->setOptions([ //set fullcalendar options
                       'firstDay' => 1,
        ])->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
                           'viewRender' => 'function() {console.log("Callbacks!");}',
                           'eventClick' => 'function(calEvent, jsEvent, view) {
                // do stuff alert(\'Event: \' + calEvent.title);
            }',
        ]);

        return view('calendar', compact('calendar'));
    }
}
