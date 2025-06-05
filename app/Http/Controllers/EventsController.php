<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
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

        $events = $eventsQuery->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'event_name' => $event->event_name,
                'description' => $event->description,
                'active_at' => $event->active_at,
                'cancelled_at' => $event->cancelled_at,
                'uri' => $event->uri,
                'organization' => [
                    'title' => $event->organization->title,
                    'category' => $event->organization->category ? [
                        'id' => $event->organization->category->id,
                        'label' => $event->organization->category->label,
                    ] : null,
                ],
                'venue' => $event->venue ? [
                    'name' => $event->venue->name,
                    'address' => $event->venue->address,
                ] : null,
            ];
        });

        $categories = Category::orderBy('label')->get(['id', 'label']);

        return Inertia::render('Events/Index', [
            'events' => $events,
            'categories' => $categories,
            'selectedCategory' => $categoryFilter,
        ]);
    }
}
