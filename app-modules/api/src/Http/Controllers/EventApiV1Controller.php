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
     * @apiResource HackGreenville\Api\Resources\Events\V1\EventCollection
     * @apiResourceModel App\Models\Event states=forDocumentation
     */
    public function __invoke(EventApiV1Request $request)
    {
        $query = Event::query()
            ->with(['organization', 'venue'])
            ->published()
            ->when($request->filled('start_date'), function (Builder $query) use ($request) {
                $query->where('active_at', '>=', $request->date('start_date')->startOfDay());
            })
            ->when($request->filled('end_date'), function (Builder $query) use ($request) {
                $query->where('active_at', '<=', $request->date('end_date')->endOfDay());
            })
            ->when($request->isNotFilled(['start_date', 'end_date']), function (Builder $query) {
                $query->where('active_at', '>=', now()->subDays(config('events-api.default_days')));
            })
            ->when($request->filled('tags'), function (Builder $query) use ($request) {
                $query->whereHas('organization.tags', function (Builder $query) use ($request) {
                    $query->whereIn('id', $request->input('tags'));
                });
            })
            ->when($request->filled('name'), function (Builder $query) use ($request) {
                $query->where('event_name', 'like', '%' . $request->input('name') . '%');
            })
            ->when($request->filled('org_name'), function (Builder $query) use ($request) {
                $query->where('group_name', 'like', '%' . $request->input('org_name') . '%');
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
                    $query->where('city', 'like', '%' . $request->input('venue_city') . '%');
                });
            })
            ->when($request->filled('venue_state'), function (Builder $query) use ($request) {
                $query->whereHas('venue', function (Builder $query) use ($request) {
                    $query->whereHas('state', function (Builder $query) use ($request) {
                        $query->where('abbr', $request->input('venue_state'));
                    });
                });
            })
            ->when($request->filled('sort_by') && in_array($request->input('sort_by'), [
                'active_at', 'event_name', 'group_name', 'rsvp_count', 'created_at'
            ]), function (Builder $query) use ($request) {
                $sortDirection = $request->input('sort_direction') === 'desc' ? 'desc' : 'asc';
                $query->orderBy($request->input('sort_by'), $sortDirection);
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
