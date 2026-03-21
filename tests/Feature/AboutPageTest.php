<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_about_page_links_to_current_api_docs(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('/docs/ORGS_API.md');
        $response->assertSee('/docs/EVENTS_API.md');
    }
}
