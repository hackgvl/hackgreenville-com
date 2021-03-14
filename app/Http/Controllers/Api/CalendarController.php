<?php

namespace App\Http\Controllers\Api;

use App\Contracts\CalendarContract;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

//use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * return events formatted
     * @param CalendarContract $calendar
     * @return JsonResponse
     */
    public function index(CalendarContract $calendar)
    {
        $start = Carbon::parse(request('start'));
        $end   = Carbon::parse(request('end'));

        Event::datesBetween($start, $end)
                ->each(
                        function ($e) use ($calendar) {
                            $calendar->addEvent(
                                    $e->active_at,
                                    $e->expire_at,
                                    $e->event_name,
                                    $e->description,
                                    false,
                                    [
                                            'location'  => $e->venue . '',
                                            'event_id'  => $e->id,
                                            'event_url' => $e->uri,
                                            'cancelled' => (bool)$e->cancelled_at,
                                    ]
                            );
                        }
                );

        return response()->json($calendar->getEvents());
    }
}
