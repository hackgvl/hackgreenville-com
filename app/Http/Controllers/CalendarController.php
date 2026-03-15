<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Stringable;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function data(Request $request)
    {
        $request->validate([
            'start' => ['required', 'before:end', 'date'],
            'end' => ['required', 'after:start', 'date'],
        ]);

        $events = Event::query()
            ->published()
            ->with('organization', 'venue')
            ->whereBetween('active_at', [
                $request->date('start')->startOfDay(),
                $request->date('end')->endOfDay(),
            ])
            ->get()
            ->map(function (Event $event) {
                $cancelled = $event->isCancelled();

                return [
                    'start' => $event->active_at,
                    'end' => $event->expire_at->copy()->addHours(2),

                    'title' => $event->organization->title . "\n" . $event->displayName(),
                    'description' => str($event->description)
                        ->markdown()
                        ->when($cancelled, fn (Stringable $str) => $str->prepend('<h3 class="text-danger">This event was cancelled</h3><br />')),

                    'allDay' => false,
                    'cancelled' => $cancelled,
                    'color' => $cancelled ? 'red' : null,
                    'add_to_google_calendar_url' => $event->toGoogleCalendarUrl(),
                    'event_url' => $event->uri,
                ];
            });

        return response()->json($events);
    }
}
