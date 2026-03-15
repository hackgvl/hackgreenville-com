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
     * Please see the [Event API docs](https://github.com/hackgvl/hackgreenville-com/blob/develop/EVENTS_API.md) for more information about the event API.
     *
     * @apiResource HackGreenville\Api\Resources\Events\V0\EventCollection
     * @apiResourceModel App\Models\Event states=forDocumentation
     */
    public function __invoke(EventApiV0Request $request)
    {
        return new EventCollection(
            resource: Event::query()
                ->with(['organization.tags', 'venue'])
                ->published()
                ->withActiveOrganization()
                ->filterByDateRange($request->date('start_date'), $request->date('end_date'))
                ->when($request->filled('tags'), function (Builder $query) use ($request) {
                    $query->whereHas('organization.tags', function (Builder $query) use ($request) {
                        $query->where('id', $request->integer('tags'));
                    });
                })
                ->oldest('active_at')
                ->get()
        );
    }
}
