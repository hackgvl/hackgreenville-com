<?php

namespace Database\Factories;

use App\Models\State;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VenueFactory extends Factory
{
    protected $model = Venue::class;

    public function definition()
    {
        return [
            'name' => $name = implode(' ', $this->faker->words(5)),
            'address' => $this->faker->address,
            'zipcode' => $this->faker->postcode,
            'country' => $this->faker->countryCode(),
            'phone' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'state_id' => State::inRandomOrder()->first(),
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
        ];
    }
}
