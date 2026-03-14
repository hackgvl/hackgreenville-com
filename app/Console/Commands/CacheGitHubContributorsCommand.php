<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CacheGitHubContributorsCommand extends Command
{
    protected $signature = 'github:cache-contributors';

    protected $description = 'Fetch contributors from all public hackgvl repos and cache the result';

    public function handle(): int
    {
        $this->info('Fetching public repos for hackgvl...');

        $repos = Http::get('https://api.github.com/orgs/hackgvl/repos', [
            'type' => 'public',
            'per_page' => 100,
        ])->throw()->json();

        $repoNames = collect($repos)->pluck('name');
        $this->info("Found {$repoNames->count()} repos.");

        $responses = Http::pool(fn ($pool) => $repoNames->map(
            fn ($repo) => $pool->get("https://api.github.com/repos/hackgvl/{$repo}/contributors", [
                'per_page' => 100,
            ])
        )->all());

        $contributors = collect($responses)
            ->filter(fn ($response) => $response->successful())
            ->flatMap(fn ($response) => $response->json() ?? [])
            ->filter(fn ($c) => ($c['type'] ?? '') === 'User' && ($c['contributions'] ?? 0) >= 1)
            ->groupBy('login')
            ->map(fn ($group) => [
                'login' => $group->first()['login'],
                'avatar_url' => $group->first()['avatar_url'],
                'html_url' => $group->first()['html_url'],
                'contributions' => $group->sum('contributions'),
            ])
            ->sortByDesc('contributions')
            ->values();

        Cache::put('github_contributors', $contributors, now()->addHours(12));

        $this->info("Cached {$contributors->count()} contributors.");

        return self::SUCCESS;
    }
}
