<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'upcoming_events' => Event::future()
                ->published()
                ->with(['organization', 'venue.state'])
                ->limit(10)
                ->get()
                ->map(function (Event $event) {
                    return [
                        'id' => $event->id,
                        'event_name' => $event->event_name,
                        'description' => $event->description,
                        'active_at' => $event->active_at->toISOString(),
                        'expire_at' => $event->expire_at ? $event->expire_at->toISOString() : null,
                        'cancelled_at' => $event->cancelled_at?->toISOString(),
                        'rsvp_count' => $event->rsvp_count,
                        'uri' => $event->uri,
                        'service' => $event->service->value ?? 'unknown',
                        'organization' => [
                            'title' => $event->organization->title,
                        ],
                        'venue' => $event->venue ? [
                            'name' => $event->venue->name,
                            'address' => $event->venue->address,
                            'city' => $event->venue->city,
                            'state' => $event->venue->state,
                        ] : null,
                    ];
                }),
        ]);
    }
}
