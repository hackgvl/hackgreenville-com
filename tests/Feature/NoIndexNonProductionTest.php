<?php

namespace Tests\Feature;

use Tests\TestCase;

class NoIndexNonProductionTest extends TestCase
{
    public function test_robots_txt_disallows_all_in_non_production(): void
    {
        $this->assertEquals('testing', app()->environment());

        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->assertStringContainsString('Disallow: /', $response->getContent());
    }

    public function test_robots_txt_allows_all_in_production(): void
    {
        app()->detectEnvironment(fn () => 'production');

        $response = $this->get('/robots.txt');

        $response->assertOk();
        $this->assertStringContainsString("User-agent: *\nDisallow:", $response->getContent());
        $this->assertStringContainsString('Sitemap:', $response->getContent());
    }

    public function test_non_production_responses_have_x_robots_tag_header(): void
    {
        $response = $this->get('/about');

        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_production_responses_do_not_have_x_robots_tag_header(): void
    {
        app()->detectEnvironment(fn () => 'production');

        $response = $this->get('/about');

        $response->assertHeaderMissing('X-Robots-Tag');
    }

    public function test_non_production_html_has_noindex_meta_tag(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_production_html_does_not_have_noindex_meta_tag(): void
    {
        app()->detectEnvironment(fn () => 'production');

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertDontSee('<meta name="robots" content="noindex, nofollow">', false);
    }
}
