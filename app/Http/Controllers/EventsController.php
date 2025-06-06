<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index()
    {
        $categoryFilter = request('category');

        $eventsQuery = Event::future()
            ->published()
            ->with(['organization.category', 'venue.state'])
            ->orderBy('active_at');

        if ($categoryFilter) {
            $eventsQuery->whereHas('organization.category', function ($query) use ($categoryFilter) {
                $query->where('id', $categoryFilter);
            });
        }

        $events = $eventsQuery->get()->map(fn (Event $event) => [
            'id' => $event->id,
            'event_name' => $event->event_name,
            'description' => str($event->description)->markdown()->toString(),
            'active_at' => $event->active_at->toISOString(),
            'expire_at' => $event->expire_at ? $event->expire_at->toISOString() : null,
            'cancelled_at' => $event->cancelled_at?->toISOString(),
            'rsvp_count' => $event->rsvp_count,
            'uri' => $event->uri,
            'service' => $event->service->value ?? 'unknown',
            'organization' => [
                'title' => $event->organization->title,
                'slug' => $event->organization->slug,
                'category' => $event->organization->category ? [
                    'id' => $event->organization->category->id,
                    'label' => $event->organization->category->label,
                ] : null,
            ],
            'venue' => $event->venue ? [
                'name' => $event->venue->name,
                'address' => $event->venue->address,
                'city' => $event->venue->city,
                'state' => $event->venue->state,
            ] : null,
        ]);

        $categories = Category::orderBy('label')->get(['id', 'label']);

        return Inertia::render('Events/Index', [
            'events' => $events,
            'categories' => $categories,
            'selectedCategory' => $categoryFilter,
        ]);
    }

    public function show(Event $event)
    {
        $event->load(['organization', 'venue.state']);

        $eventData = [
            'id' => $event->id,
            'event_name' => $event->event_name,
            'description' => str($event->description)->markdown()->toString(),
            'active_at' => $event->active_at->toISOString(),
            'expire_at' => $event->expire_at ? $event->expire_at->toISOString() : null,
            'cancelled_at' => $event->cancelled_at?->toISOString(),
            'rsvp_count' => $event->rsvp_count,
            'uri' => $event->uri,
            'service' => $event->service->value ?? 'unknown',
            'organization' => [
                'id' => $event->organization->id,
                'title' => $event->organization->title,
                'slug' => $event->organization->slug,
            ],
            'venue' => $event->venue ? [
                'name' => $event->venue->name,
                'address' => $event->venue->address,
                'city' => $event->venue->city,
                'state' => $event->venue->state,
            ] : null,
            'google_calendar_url' => $event->toGoogleCalendarUrl(),
        ];

        return Inertia::render('Events/Show', [
            'event' => $eventData,
        ]);
    }
}
