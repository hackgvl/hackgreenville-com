<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use HackGreenville\Api\Http\Requests\EventApiV1Request;
use HackGreenville\Api\Resources\Events\V1\EventCollection;
use Illuminate\Database\Eloquent\Builder;

class EventApiV1Controller extends Controller
{
    /**
     * Events API v1
     *
     * This API provides access to event data stored in the HackGreenville database.
     *
     * * Please see the [Event API docs](https://github.com/hackgvl/hackgreenville-com/blob/develop/EVENTS_API.md) for more information about the event API.
     *
     * @apiResource HackGreenville\Api\Resources\Events\V1\EventCollection
     * @apiResourceModel App\Models\Event states=forDocumentation
     */
    public function __invoke(EventApiV1Request $request)
    {
        $query = Event::query()
            ->with(['organization', 'venue'])
            ->published()
            ->withActiveOrganization()
            ->filterByDateRange($request->date('start_date'), $request->date('end_date'))
            ->when($request->filled('tags'), function (Builder $query) use ($request) {
                $query->whereHas('organization.tags', function (Builder $query) use ($request) {
                    $query->whereIn('id', $request->input('tags'));
                });
            })
            ->when($request->filled('name'), function (Builder $query) use ($request) {
                $name = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $request->input('name'));
                $query->whereRaw("event_name LIKE ? ESCAPE '!'", ['%' . $name . '%']);
            })
            ->when($request->filled('org_name'), function (Builder $query) use ($request) {
                $orgName = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $request->input('org_name'));
                $query->whereRaw("group_name LIKE ? ESCAPE '!'", ['%' . $orgName . '%']);
            })
            ->when($request->filled('service'), function (Builder $query) use ($request) {
                $query->where('service', $request->input('service'));
            })
            ->when($request->filled('min_rsvp'), function (Builder $query) use ($request) {
                $query->where('rsvp_count', '>=', $request->integer('min_rsvp'));
            })
            ->when($request->filled('max_rsvp'), function (Builder $query) use ($request) {
                $query->where('rsvp_count', '<=', $request->integer('max_rsvp'));
            })
            ->when($request->filled('venue_city'), function (Builder $query) use ($request) {
                $query->whereHas('venue', function (Builder $query) use ($request) {
                    $city = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $request->input('venue_city'));
                    $query->whereRaw("city LIKE ? ESCAPE '!'", ['%' . $city . '%']);
                });
            })
            ->when($request->filled('venue_state'), function (Builder $query) use ($request) {
                $query->whereHas('venue', function (Builder $query) use ($request) {
                    $query->where('state', $request->input('venue_state'));
                });
            })
            ->when($request->filled('sort_by'), function (Builder $query) use ($request) {
                $query->orderBy($request->input('sort_by'), $request->input('sort_direction', 'asc'));
            }, function (Builder $query) {
                $query->orderBy('active_at', 'asc');
            })
            ->when($request->filled('is_paid'), function (Builder $query) use ($request) {
                $param = $request->input('is_paid');
                $is_null = $param === "null";

                if ($is_null) {
                    return $query->whereNull('is_paid');
                }

                $query->where('is_paid', $param === "true");
            });

        $perPage = $request->input('per_page', 15);
        $events = $query->paginate($perPage);

        return new EventCollection($events);
    }
}
