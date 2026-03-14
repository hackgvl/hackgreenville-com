<?php

namespace HackGreenville\SlackEventsBot\Tests\Models;

use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Models\SlackCooldown;
use PHPUnit\Framework\Attributes\Test;
use Tests\DatabaseTestCase;

class SlackCooldownTest extends DatabaseTestCase
{
    #[Test]
    public function it_casts_expires_at_to_datetime(): void
    {
        $cooldown = SlackCooldown::create([
            'accessor' => 'test-workspace',
            'resource' => 'check_api',
            'expires_at' => '2026-03-15 12:00:00',
        ]);

        $cooldown->refresh();

        $this->assertInstanceOf(Carbon::class, $cooldown->expires_at);
        $this->assertEquals('2026-03-15 12:00:00', $cooldown->expires_at->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $cooldown = SlackCooldown::create([
            'accessor' => 'test-accessor',
            'resource' => 'test-resource',
            'expires_at' => now()->addMinutes(15),
        ]);

        $this->assertEquals('test-accessor', $cooldown->accessor);
        $this->assertEquals('test-resource', $cooldown->resource);
        $this->assertNotNull($cooldown->expires_at);
    }
}
