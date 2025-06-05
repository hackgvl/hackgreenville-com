<?php

namespace App\Http\Controllers;

use App\Models\Org;
use Illuminate\Contracts\Database\Query\Builder;
use Inertia\Inertia;

class OrgsController extends Controller
{
    public function index()
    {
        $organizations = Org::with('category')
            ->withCount('events')
            ->orderBy('status', 'desc') // Active first (since 'active' > 'inactive' alphabetically)
            ->orderBy('title')
            ->get()
            ->map(function ($org) {
                return [
                    'id' => $org->id,
                    'title' => $org->title,
                    'description' => $org->description,
                    'slug' => $org->slug,
                    'focus_area' => $org->focus_area,
                    'established_at' => $org->established_at,
                    'events_count' => $org->events_count,
                    'status' => $org->status->value,
                    'status_label' => $org->status->getLabel(),
                ];
            });

        return Inertia::render('Organizations/Index', [
            'organizations' => $organizations,
        ]);
    }

    public function show(Org $org)
    {
        return Inertia::render('Organizations/Show', [
            'org' => $org->load([
                'events' => function (Builder $query) {
                    $query
                        ->with('organization', 'venue.state')
                        ->future()
                        ->published()
                        ->orderBy('active_at')
                        ->limit(25);
                },
            ]),
        ]);
    }

    public function inactive()
    {
        $inactiveOrgs = Org::with('category')->onlyTrashed()->get();

        return Inertia::render('Organizations/Inactive', [
            'organizations' => $inactiveOrgs,
        ]);
    }
}
