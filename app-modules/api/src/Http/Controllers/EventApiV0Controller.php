<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Models\Event;
use HackGreenville\Api\Http\Requests\EventApiV0Request;
use HackGreenville\Api\Resources\Events\V0\EventCollection;
use Illuminate\Database\Eloquent\Builder;

class EventApiV0Controller
{
    /**
     * Events API v0
     *
     * This API provides access to event data stored in the HackGreenville database.
     *
     * @apiResource HackGreenville\Api\Resources\Events\V0\EventCollection
     * @apiResourceModel App\Models\Event
     */
    public function __invoke(EventApiV0Request $request)
    {
        return new EventCollection(
            resource: Event::query()
                ->with(['organization.tags', 'venue.state'])
                ->published()
                ->when($request->filled('start_date'), function (Builder $query) use ($request) {
                    $query->where('active_at', '>=', $request->date('start_date')->startOfDay());
                })
                ->when($request->filled('end_date'), function (Builder $query) use ($request) {
                    $query->where('active_at', '<=', $request->date('end_date')->endOfDay());
                })
                ->when($request->filled('tags'), function (Builder $query) use ($request) {
                    $query->whereHas('organization.tags', function (Builder $query) use ($request) {
                        $query->where('id', $request->integer('tags'));
                    });
                })
                ->when($request->isNotFilled(['start_date', 'end_date']), function (Builder $query) {
                    $query->where('active_at', '>=', now()->subDays(config('events-api.default_days')));
                })
                ->oldest('active_at')
                ->get()
        );
    }
}
