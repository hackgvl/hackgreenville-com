<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_about_page_links_to_current_api_docs(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('<a href="/docs/ORGS_API.md"', false);
        $response->assertSee('<a href="/docs/EVENTS_API.md"', false);
    }
}
