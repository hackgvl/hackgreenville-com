<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Models\Event;
use HackGreenville\Api\Http\Requests\EventApiV0Request;
use HackGreenville\Api\Resources\Events\V0\EventCollection;
use Illuminate\Database\Eloquent\Builder;

class EventApiV0Controller
{
    public function __invoke(EventApiV0Request $request)
    {
        return new EventCollection(
            resource: Event::query()
                ->when($request->filled('start_date'), function (Builder $query) use ($request) {
                    $query->where('active_at', '>=', $request->date('start_date'));
                })
                ->when($request->filled('end_date'), function (Builder $query) use ($request) {
                    $query->where('active_at', '<=', $request->date('end_date'));
                })
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
