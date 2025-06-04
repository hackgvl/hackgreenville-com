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
            ->orderBy('title')
            ->get();

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
