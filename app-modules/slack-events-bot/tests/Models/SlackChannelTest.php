<?php

namespace HackGreenville\SlackEventsBot\Tests\Models;

use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use PHPUnit\Framework\Attributes\Test;
use Tests\DatabaseTestCase;

class SlackChannelTest extends DatabaseTestCase
{
    #[Test]
    public function it_has_many_messages(): void
    {
        $channel = SlackChannel::factory()->create();
        $message = SlackMessage::factory()->create(['channel_id' => $channel->id]);

        $this->assertTrue($channel->messages->contains($message));
        $this->assertCount(1, $channel->messages);
    }

    #[Test]
    public function it_belongs_to_a_workspace(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $channel = SlackChannel::factory()->create(['slack_workspace_id' => $workspace->id]);

        $this->assertInstanceOf(SlackWorkspace::class, $channel->workspace);
        $this->assertEquals($workspace->id, $channel->workspace->id);
    }

    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $channel = SlackChannel::factory()->create([
            'slack_channel_id' => 'C1234567890',
        ]);

        $this->assertEquals('C1234567890', $channel->slack_channel_id);
    }
}
