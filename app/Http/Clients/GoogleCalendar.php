<?php


namespace App\Http\Clients;


use App\Contracts\CalendarContract;
use Carbon\Carbon;

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

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param string $title
     * @param string $description
     * @param bool   $allDay
     * @param array  $attributes
     * @return GoogleCalendar
     */
    public function addEvent($start, $end, $title, $description, $allDay = false, $attributes = [])
    {
        if (!$end) {
            // Default to start + 2 hours.
            $end = $start->addHours(2);
        }

        $google_format = 'Ymd\THms\Z';
        $start_fmt     = $start->format($google_format);
        $end_fmt       = $end->format($google_format);

        $new_event = compact('start', 'end', 'title', 'description', 'allDay', 'start_fmt', 'end_fmt');
        $new_event = array_merge($new_event, $attributes);

        $this->events[] = $new_event;

        return $this;
    }

    public function getEvent()
    {
        return json_encode($this->events);
    }
}
