<?php


namespace App\Http\Clients;


use App\Contracts\CalendarContract;

class GoogleCalendar extends CalendarContract
{

    public function js()
    {
        $events = $this->getEvent();

        return view('calendar.js', compact('events'));

    }

    public function html()
    {
        return view('calendar.html');
    }

    public function initialize($events = [])
    {
        $this->events = $events;

        return $this;
    }

    public function addEvent($start, $end, $title, $description, $allDay = false, ...$attributes)
    {
        $new_event = compact('start', 'end', 'title', 'description', 'allDay');
        $new_event = array_merge($new_event, $attributes);

        $this->events[] = $new_event;
    }

    public function getEvent()
    {
        return json_encode($this->events);
    }
}
