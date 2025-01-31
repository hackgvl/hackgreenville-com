<?php

namespace App\Http\Controllers;

use App\Enums\OrganizationStatus;
use App\Models\Event;
use App\Models\Org;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as CalendarEvent;

class CalendarFeedController extends Controller
{
    public function index()
    {
        return view('calendar-feed.index', [
            'organizations' => Org::query()
                ->with('category')
                ->where('status', OrganizationStatus::Active)
                ->orderBy('title')
                ->get()
                ->map(fn (Org $org) => [
                    'id' => $org->id,
                    'title' => $org->title,
                    'checked' => true,
                ]),
        ]);
    }

    public function show(Request $request)
    {
        $organization_ids = collect(explode('-', $request->input('orgs')));

        $organizations = Org::query()
            ->where('status', OrganizationStatus::Active)
            ->when($organization_ids->isNotEmpty(), fn ($query) => $query->whereIn('id', $organization_ids))
            ->get();

        $events = Event::query()
            ->with('organization', 'venue.state')
            ->future()
            ->when($organization_ids->isNotEmpty(), fn ($query) => $query->whereIn('organization_id', $organization_ids))
            ->get()
            ->mapWithKeys(fn (Event $event, $i) => [
                $i => CalendarEvent::create($event->event_name)
                    ->uniqueIdentifier($event->uniqueIdentifierHash())
                    ->startsAt($event->active_at)
                    ->endsAt($event->expire_at)
                    ->address($event->venue?->fullAddress() ?? 'Virtual Event')
                    ->description($event->description)
                    ->url($event->url),
            ])
            ->toArray();

        // Calendar
        $calendar = Calendar::create()
            ->timezone(['America/New_York']);

        if ($organizations->count() === 1) {
            $organization = $organizations->first();

            $calendar
                ->productIdentifier($organization->title . ' Event Calendar')
                ->name($organization->title . ' Event Calendar')
                ->description($organization->description ?? '')
                ->source(route('orgs.show', $organization));
        } else {
            $calendar
                ->productIdentifier('HackGreenville.com Event Calendar')
                ->name('HackGreenville Event Calendar')
                ->description('Tech Events in Greenville SC!')
                ->source(route('calendar.index'));
        }

        $data = $calendar->event($events)->get();

        return response($data)
            ->header('Content-Type', 'text/calendar; charset=utf-8');
    }
}
