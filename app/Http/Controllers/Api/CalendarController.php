<?php

namespace App\Http\Controllers\Api;

use App\Http\Clients\GoogleCalendar;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;

//use Illuminate\Http\Request;

class CalendarController extends Controller
{
	public function index()
	{
        $calendar = new GoogleCalendar();
        $date     = (new Carbon(request('start', '-1 months')))->addMonths(1);

        // TODO :: for now don't show the cancelled events but add them back in but show that its been cancelled on the calendar by
        // TODO :: changing the background red or something
        $events = Event::dateIsLike($date)->whereNull('cancelled_at')->get();

        foreach ($events as $e) {
            $attributes = [
                'location'  => $e->venue . '',
                'event_id'  => $e->id,
                'event_url' => $e->uri,
            ];

            $calendar->addEvent($e->active_at, $e->expire_at, $e->event_name, $e->description, false, $attributes);
        }

		return response()->json($calendar->getEvents());
	}
}
