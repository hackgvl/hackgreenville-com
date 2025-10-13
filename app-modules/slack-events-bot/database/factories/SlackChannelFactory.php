<?php

namespace HackGreenville\SlackEventsBot\Database\Factories;

use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlackChannelFactory extends Factory
{
    protected $model = SlackChannel::class;

    public function definition(): array
    {
        return [
            'slack_channel_id' => $this->faker->unique()->regexify('[C][A-Z0-9]{10}'),
            'slack_workspace_id' => SlackWorkspace::factory(),
        ];
    }
}
