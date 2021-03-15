<?php

namespace Database\Factories;

use App\Models\State;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VenueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Venue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                'name'     => $name = implode(' ', $this->faker->words(5)),
                'slug'     => Str::slug($name),
                'address'  => $this->faker->address,
                'zipcode'  => $this->faker->postcode,
                'phone'    => $this->faker->phoneNumber,
                'city'     => $this->faker->city,
                'state_id' => State::factory(),
                'lat'      => $this->faker->latitude,
                'lng'      => $this->faker->longitude,
        ];
    }
}
