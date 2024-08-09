<?php

namespace App\Http\Controllers;

use App\Models\Org;
use Illuminate\Contracts\Database\Query\Builder;

class OrgsController extends Controller
{
    public function index()
    {
        $activeOrgs = Org::with('category')
            ->orderBy('title')
            ->get()
            ->sortBy(function (Org $org) {
                return $org->category->isInactive()
                    ? PHP_INT_MAX
                    : $org->category->count();
            }, SORT_NUMERIC)
            ->groupBy('category_id');

        return view('orgs.index', compact('activeOrgs'));
    }

    public function show(Org $org)
    {
        return view('orgs.show', [
            'org' => $org->load([
                'events' => function (Builder $query) {
                    $query
                        ->with('organization', 'venue.state')
                        ->future()
                        ->published()
                        ->orderBy('active_at')
                        ->limit(5);
                },
            ]),
        ]);
    }

    public function inactive()
    {
        $inactiveOrgs = Org::with('category')->onlyTrashed()->get()->groupBy('category_id');

        return view('orgs.inactive', compact('inactiveOrgs'));
    }
}
