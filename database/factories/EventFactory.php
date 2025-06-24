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
            'is_paid' => false,
        ];
    }
}
