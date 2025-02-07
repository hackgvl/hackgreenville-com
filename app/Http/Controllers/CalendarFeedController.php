<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarFeedRequest;
use App\Models\Event;
use App\Models\Org;
use Illuminate\Contracts\Database\Query\Builder;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as CalendarEvent;
use Spatie\IcalendarGenerator\Enums\Classification;
use Spatie\IcalendarGenerator\Enums\EventStatus;

class CalendarFeedController extends Controller
{
    public function index(CalendarFeedRequest $request)
    {
        return view('calendar-feed.index', [
            'organizations' => Org::query()
                ->with('category')
                ->active()
                ->when(
                    value: $request->validOrganizations()->isNotEmpty(),
                    callback: function (Builder $query) use ($request) {
                        $query->orderByFieldSequence('id', $request->validOrganizations()->pluck('id')->toArray())
                            ->orderBy('title');
                    },
                    default: fn (Builder $query) => $query->orderBy('title')
                )
                ->get()
                ->map(fn (Org $org) => [
                    'id' => $org->id,
                    'title' => $org->title,
                    'checked' => $request->validOrganizations()->contains('id', $org->id),
                ]),
        ]);
    }

    public function show(CalendarFeedRequest $request)
    {
        $events = Event::query()
            ->with('organization', 'venue.state')
            ->future()
            ->when($request->validOrganizations()->isNotEmpty(), fn ($query) => $query->whereIn('organization_id', $request->validOrganizations()->pluck('id')))
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

        if ($request->validOrganizations()->count() === 1) {
            $organization = $request->validOrganizations()->first();

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
