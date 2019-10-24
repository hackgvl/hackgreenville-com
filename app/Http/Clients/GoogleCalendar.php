<?php


namespace App\Http\Clients;


use App\Contracts\CalendarContract;

class GoogleCalendar extends CalendarContract
{

    public function js()
    {
        $events = $this->getEvent();

        $js = <<<JS
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid' ],
        eventSources: [{events: {$events}}],
        eventClick: function(info) {
            console.log(info);
            // change the border color just for fun
            
            info.el.style.borderColor = info.el.style.borderColor == 'red'? 'black': 'red';
        }
    });
    
    calendar.render();
});
JS;

        return $js;

    }

    public function html()
    {
        return "<div id='calendar'></div>";
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
