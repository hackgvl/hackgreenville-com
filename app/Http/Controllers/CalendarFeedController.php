<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Org;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as CalendarEvent;
use Spatie\IcalendarGenerator\Enums\Classification;
use Spatie\IcalendarGenerator\Enums\EventStatus;

class CalendarFeedController extends Controller
{
    public function index(Request $request)
    {
        $orgs = Org::query()
            ->select('id')
            ->active()
            ->when($request->filled('orgs'), fn (Builder $query) => $query->whereIn('id', explode('-', $request->input('orgs'))))
            ->get();

        return view('calendar-feed.index', [
            'organizations' => Org::query()
                ->with('category')
                ->active()
                ->when(
                    value: $orgs->isNotEmpty(),
                    callback: function (Builder $query) use ($orgs) {
                        $query->orderByFieldSequence('id', $orgs->pluck('id')->toArray())
                            ->orderBy('title');
                    },
                    default: function (Builder $query) {
                        $query->orderBy('title');
                    }
                )
                ->get()
                ->map(fn (Org $org) => [
                    'id' => $org->id,
                    'title' => $org->title,
                    'checked' => $orgs->contains('id', $org->id),
                ]),
        ]);
    }

    public function show(Request $request)
    {
        $organization_ids = collect(explode('-', $request->input('orgs', '')))
            ->take(150) // Only allow up to 150 orgs, could prevent unnecessary db lookups.
            ->filter(fn ($id) => is_numeric($id) && (int) $id > 0);

        $organizations = Org::query()
            ->active()
            ->when($organization_ids->isNotEmpty(), fn ($query) => $query->whereIn('id', $organization_ids))
            ->get();

        $events = Event::query()
            ->with('organization', 'venue.state')
            ->future()
            ->when($organization_ids->isNotEmpty(), fn ($query) => $query->whereIn('organization_id', $organization_ids))
            ->get()
            ->mapWithKeys(fn (Event $event, $i) => [
                $i => CalendarEvent::create($event->event_name)
                    ->uniqueIdentifier(sha1($event->id))
                    ->startsAt($event->active_at)
                    ->endsAt($event->expire_at)
                    ->status(match ($event->isCancelled()) {
                        true => EventStatus::cancelled(),
                        default => EventStatus::confirmed(),
                    })
                    ->address(
                        address: $event->venue?->fullAddress() ?? 'Virtual Event',
                        name: $event->venue?->name ?? 'Virtual'
                    )
                    ->classification(Classification::public())
                    ->description("Check out latest event details at {$event->url}")
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
