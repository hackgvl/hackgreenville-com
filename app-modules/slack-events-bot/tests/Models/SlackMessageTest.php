<?php

namespace HackGreenville\SlackEventsBot\Tests\Models;

use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use PHPUnit\Framework\Attributes\Test;
use Tests\DatabaseTestCase;

class SlackMessageTest extends DatabaseTestCase
{
    #[Test]
    public function it_belongs_to_a_channel(): void
    {
        $channel = SlackChannel::factory()->create();
        $message = SlackMessage::factory()->create(['channel_id' => $channel->id]);

        $this->assertInstanceOf(SlackChannel::class, $message->channel);
        $this->assertEquals($channel->id, $message->channel->id);
    }

    #[Test]
    public function it_casts_week_to_datetime(): void
    {
        $week = Carbon::parse('2026-03-09')->startOfWeek();
        $message = SlackMessage::factory()->create(['week' => $week]);

        $message->refresh();

        $this->assertInstanceOf(Carbon::class, $message->week);
    }

    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $channel = SlackChannel::factory()->create();

        $message = SlackMessage::factory()->create([
            'week' => now()->startOfWeek(),
            'message_timestamp' => '1234567890.123456',
            'message' => 'Test message content',
            'sequence_position' => 2,
            'channel_id' => $channel->id,
        ]);

        $this->assertEquals('1234567890.123456', $message->message_timestamp);
        $this->assertEquals('Test message content', $message->message);
        $this->assertEquals(2, $message->sequence_position);
        $this->assertEquals($channel->id, $message->channel_id);
    }
}
