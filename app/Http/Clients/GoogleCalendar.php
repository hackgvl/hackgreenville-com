<?php


namespace App\Http\Clients;


use App\Contracts\CalendarContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class GoogleCalendar extends CalendarContract
{

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
    public function addEvent(
            Carbon $start,
            Carbon $end = null,
            string $title,
            string $description,
            bool $allDay = false,
            array $attributes = []
    ) {
        if (!$end) {
            // Default to start + 2 hours.
            $end = $start->clone()->addHours(2);
        }

        // google calender when adding from a link expects the time to be in utc time with ISO formatting
        $google_format = 'Ymd\THi00\Z';
        $start_fmt     = $start->clone()->utc()->format($google_format);
        $end_fmt       = $end->clone()->utc()->format($google_format);

        // push the data to the events
        $new_event = compact('start', 'end', 'title', 'description', 'allDay', 'start_fmt', 'end_fmt');
        $new_event = array_merge($new_event, $attributes);

        $this->events[] = $new_event;

        return $this;
    }

    public function getEvents()
    {
        return (new Collection($this->events))
                ->map(
                        function ($event) {
                            if ($event['cancelled']) {
                                $event['color']       = 'red';
                                $event['title']       = '[CANCELLED] ' . $event['title'];
                                $event['description'] = '<h3 class="text-danger">This event was cancelled</h3><br />' . $event['description'];
                            }

                            return $event;
                        }
                );
    }
}
