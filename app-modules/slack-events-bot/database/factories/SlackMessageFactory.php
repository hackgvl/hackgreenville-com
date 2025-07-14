<?php

namespace HackGreenville\SlackEventsBot\Database\Factories;

use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SlackMessageFactory extends Factory
{
    protected $model = SlackMessage::class;

    public function definition(): array
    {
        return [
            'week' => Carbon::now()->startOfWeek(),
            'message' => $this->faker->sentence,
            'message_timestamp' => now()->timestamp . '.' . $this->faker->randomNumber(6, true),
            'sequence_position' => $this->faker->numberBetween(0, 10),
            'channel_id' => SlackChannel::factory(),
        ];
    }
}
