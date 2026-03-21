<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LabsContributorsTest extends TestCase
{
    public function test_contributors_are_displayed_on_labs_page(): void
    {
        Cache::put('github_contributors', collect([
            ['login' => 'alice', 'avatar_url' => 'https://avatars.githubusercontent.com/u/1', 'html_url' => 'https://github.com/alice', 'contributions' => 50],
            ['login' => 'bob', 'avatar_url' => 'https://avatars.githubusercontent.com/u/2', 'html_url' => 'https://github.com/bob', 'contributions' => 30],
        ]));

        $response = $this->get('/labs');

        $response->assertStatus(200);
        $response->assertSee('Built by the Community');
        $response->assertSee('2 contributors and counting');
        $response->assertSee('https://github.com/alice');
        $response->assertSee('https://github.com/bob');
    }

    public function test_labs_page_loads_with_empty_cache(): void
    {
        Cache::forget('github_contributors');

        $response = $this->get('/labs');

        $response->assertStatus(200);
        $response->assertSee('Want to contribute?');
        $response->assertDontSee('Built by the Community');
        $response->assertSee('<a href="/docs/EVENTS_API.md"', false);
        $response->assertSee('<a href="/docs/ORGS_API.md"', false);
    }

    public function test_command_fetches_and_caches_contributors(): void
    {
        $this->fakeGitHub(['hackgreenville-com', 'OpenData'], [
            'hackgreenville-com' => [
                ['login' => 'alice', 'avatar_url' => 'https://avatars.githubusercontent.com/u/1', 'html_url' => 'https://github.com/alice', 'contributions' => 50, 'type' => 'User'],
                ['login' => 'bob', 'avatar_url' => 'https://avatars.githubusercontent.com/u/2', 'html_url' => 'https://github.com/bob', 'contributions' => 30, 'type' => 'User'],
            ],
            'OpenData' => [
                ['login' => 'carol', 'avatar_url' => 'https://avatars.githubusercontent.com/u/3', 'html_url' => 'https://github.com/carol', 'contributions' => 10, 'type' => 'User'],
            ],
        ]);

        $this->artisan('github:cache-contributors')->assertSuccessful();

        $cached = Cache::get('github_contributors');
        $this->assertCount(3, $cached);
        $this->assertEquals('alice', $cached[0]['login']);
    }

    public function test_command_deduplicates_contributors_across_repos(): void
    {
        $this->fakeGitHub(['repo-a', 'repo-b'], [
            'repo-a' => [
                ['login' => 'alice', 'avatar_url' => 'https://avatars.githubusercontent.com/u/1', 'html_url' => 'https://github.com/alice', 'contributions' => 20, 'type' => 'User'],
            ],
            'repo-b' => [
                ['login' => 'alice', 'avatar_url' => 'https://avatars.githubusercontent.com/u/1', 'html_url' => 'https://github.com/alice', 'contributions' => 15, 'type' => 'User'],
            ],
        ]);

        $this->artisan('github:cache-contributors')->assertSuccessful();

        $cached = Cache::get('github_contributors');
        $this->assertCount(1, $cached);
        $this->assertEquals(35, $cached[0]['contributions']);
    }

    public function test_command_filters_bots_and_zero_contributions(): void
    {
        $this->fakeGitHub(['hackgreenville-com'], [
            'hackgreenville-com' => [
                ['login' => 'alice', 'avatar_url' => 'https://avatars.githubusercontent.com/u/1', 'html_url' => 'https://github.com/alice', 'contributions' => 50, 'type' => 'User'],
                ['login' => 'dependabot[bot]', 'avatar_url' => 'https://avatars.githubusercontent.com/u/99', 'html_url' => 'https://github.com/apps/dependabot', 'contributions' => 100, 'type' => 'Bot'],
                ['login' => 'ghost', 'avatar_url' => 'https://avatars.githubusercontent.com/u/3', 'html_url' => 'https://github.com/ghost', 'contributions' => 0, 'type' => 'User'],
            ],
        ]);

        $this->artisan('github:cache-contributors')->assertSuccessful();

        $cached = Cache::get('github_contributors');
        $this->assertCount(1, $cached);
        $this->assertEquals('alice', $cached[0]['login']);
    }

    private function fakeGitHub(array $repos, array $contributorsByRepo): void
    {
        $fakes = [
            'api.github.com/orgs/hackgvl/repos*' => Http::response(
                collect($repos)->map(fn ($name) => ['name' => $name])->all()
            ),
        ];

        foreach ($contributorsByRepo as $repo => $contributors) {
            $fakes["api.github.com/repos/hackgvl/{$repo}/contributors*"] = Http::response($contributors);
        }

        Http::fake($fakes);
    }
}
