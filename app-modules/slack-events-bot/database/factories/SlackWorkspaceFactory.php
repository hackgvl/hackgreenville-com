<?php

namespace HackGreenville\SlackEventsBot\Database\Factories;

use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlackWorkspaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SlackWorkspace::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'team_id' => $this->faker->unique()->regexify('[T][A-Z0-9]{10}'),
            'team_name' => $this->faker->company,
            'access_token' => $this->faker->sha256,
            'bot_user_id' => $this->faker->unique()->regexify('[U][A-Z0-9]{10}'),
        ];
    }
}
