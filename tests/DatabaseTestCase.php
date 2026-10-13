<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;

class DatabaseTestCase extends TestCase
{
    use RefreshDatabase;

    // Get the text content of an element by its data-testid attribute.
    // If the attribute is not found, returns null.
    protected function textOfTestId(TestResponse $response, string $testId): string | null
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($response->getContent());

        $node = (new \DOMXPath($dom))
            ->query("//*[@data-testid='{$testId}']")
            ->item(0);

        return $node ? mb_trim(preg_replace('/\s+/', ' ', $node->textContent)) : null;
    }
}
