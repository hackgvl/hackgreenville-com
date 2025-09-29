<?php

namespace Database\Factories;

use App\Enums\EventServices;
use App\Models\Event;
use App\Models\Org;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $venue = Venue::factory();

        return [
            'event_name' => $this->faker->sentence,
            'group_name' => $this->faker->name . ' tech group',
            'description' => $this->faker->text(100),
            'rsvp_count' => $this->faker->randomNumber(2),
            'active_at' => $active_at = now(),
            'uri' => $this->faker->url,
            'venue_id' => $venue,
            'event_uuid' => $this->faker->uuid,
            'expire_at' => $this->faker->dateTimeBetween($active_at, '+2 days'),
            'cancelled_at' => null,
            'service' => EventServices::EventBrite->value,
            'service_id' => $this->faker->randomDigit(),
            'organization_id' => Org::factory(),
            'is_paid' => null,
        ];
    }

    public function forDocumentation()
    {
        return $this->state(function (array $attributes) {
            // Use a seeded faker instance for consistent values
            $this->faker->seed(1234);

            $venue = Venue::factory();
            $org = Org::factory();

            return [
                'event_name' => $this->faker->sentence,
                'group_name' => $this->faker->name . ' tech group!!!',
                'description' => $this->faker->text(100),
                'rsvp_count' => $this->faker->randomNumber(2),
                'uri' => $this->faker->url,
                'venue_id' => $venue,
                'event_uuid' => $this->faker->uuid,
                'cancelled_at' => null,
                'service' => EventServices::EventBrite->value,
                'service_id' => $this->faker->randomDigit(),
                'organization_id' => $org,
                'is_paid' => null,
                'created_at' => '2025-01-01T12:00:00.000000Z',
                'updated_at' => '2025-01-01T12:00:00.000000Z',
                'active_at' => '2025-01-01T12:00:00.000000Z',
                'expire_at' => '2025-01-01T14:00:00.000000Z',
            ];
        });
    }
}
