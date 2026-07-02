<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Org;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    // Homepage aggregates only change via event imports and admin edits,
    // so serve them from the cache instead of querying on every request.
    private const STATS_CACHE_SECONDS = 60 * 60 * 24;

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
                'orgs' => Cache::remember(
                    'home.org-count',
                    self::STATS_CACHE_SECONDS,
                    fn () => Org::query()
                        ->active()
                        ->count()
                ),
                // Keyed by month so the count rolls over immediately on the
                // 1st instead of serving last month's cached value
                'events_this_month' => Cache::remember(
                    'home.event-count.' . now()->format('Y-m'),
                    self::STATS_CACHE_SECONDS,
                    fn () => Event::query()
                        ->published()
                        ->withActiveOrganization()
                        ->whereNull('cancelled_at')
                        ->ongoingBetween(now()->startOfMonth(), now()->endOfMonth())
                        ->count()
                ),
                'active_individuals' => config('community.active_individuals'),
                'slack_members' => config('community.slack_members'),
            ],
            'categories' => Cache::remember(
                'home.categories',
                self::STATS_CACHE_SECONDS,
                fn () => Category::query()
                    ->where('label', '!=', 'Inactive')
                    ->orderBy('label')
                    ->pluck('label')
            ),
        ]);
    }
}
