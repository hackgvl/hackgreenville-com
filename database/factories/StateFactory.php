<?php

/** @var Factory $factory */

use App\Models\State;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(State::class, function (Faker $faker) {
    return [
        'abbr' => $faker->text(5),
        'name' => $faker->text(10),
    ];
});
