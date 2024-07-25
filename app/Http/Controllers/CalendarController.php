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
            ->whereBetween('active_at', [
                $request->date('start'),
                $request->date('end'),
            ])
            ->get()
            ->map(function(Event $event) {
                return [
                    'start' => $event->active_at,
                    'start_fmt' => $event->active_at->copy()->utc()->format('Ymd\THi00\Z'),
                    // FIXME - this needs to rely on expires_at, fix importers to look for event end dates.
                    'end' => $event->active_at->copy()->addHours(2),
                    'end_fmt' => $event->active_at->copy()->addHours(2)->utc()->format('Ymd\THi00\Z'),

                    'title' => str($event->organization->title."\n".$event->event_name)
                        ->when($event->isCancelled(), fn(Stringable $str) => $str->prepend('[CANCELLED] '))
                        ->toString(),
                    'description' => str($event->description)
                        ->when($event->isCancelled(), fn(Stringable $str) => $str->prepend('<h3 class="text-danger">This event was cancelled</h3><br />')),
                    'allDay' => false,
                    'cancelled' => $event->isCancelled(),
                    'color' => match (true) {
                        $event->isCancelled() => 'red',
                        default => null,
                    },
                ];
            });

        return response()->json($events);
    }
}
