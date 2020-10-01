<?php

/** @var Factory $factory */

use App\Models\State;
use App\Models\Venue;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(Venue::class, function (Faker $faker) {
    return [
        'name'     => $name = implode(' ', $faker->words(5)),
        'slug'     => Str::slug($name),
        'address'  => $faker->address,
        'zipcode'  => $faker->postcode,
        'phone'    => $faker->phoneNumber,
        'city'     => $faker->city,
        'state_id' => factory(State::class),
        'lat'      => $faker->latitude,
        'lng'      => $faker->longitude,
    ];
});
