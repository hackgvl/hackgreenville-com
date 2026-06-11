<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Org;

class HomeController extends Controller
{
    public function index()
    {
        return view('index', [
            'upcoming_events' => Event::ongoingAndFuture()
                ->published()
                ->with('organization', 'venue')
                ->oldest('active_at')
                ->limit(5)
                ->get(),
            'stats' => [
                'orgs' => Org::query()
                    ->whereHas('category', fn ($query) => $query->where('label', '!=', 'Inactive'))
                    ->count(),
            ],
            'categories' => Category::query()
                ->where('label', '!=', 'Inactive')
                ->orderBy('label')
                ->pluck('label'),
        ]);
    }
}
