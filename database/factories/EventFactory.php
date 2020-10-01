<?php

/** @var Factory $factory */

use App\Models\Event;
use App\Models\Venue;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Event::class, function (Faker $faker) {
    $venue = factory(Venue::class);
    return [
        'event_name'   => $event_name = $faker->name . ' tech talk',
        'group_name'   => $group_name = $faker->name . ' tech group',
        'description'  => $description = $faker->text(100),
        'rsvp_count'   => $rsvp_count = $faker->randomNumber(2),
        'active_at'    => $active_at = $faker->dateTime('+2 hours'),
        'uri'          => $url = $faker->url,
        'venue_id'     => $venue,
        'event_uuid'   => $uuid = $faker->uuid,
        'expire_at'    => $expire_at = $faker->dateTimeBetween($active_at, '+2 days'),
        'cancelled_at' => null,

        'cache' => [
            [
                "created_at"  => "2019-12-09T21:24:53Z",
                "data_as_of"  => "2020-09-09T18:40:10Z",
                "description" => $description,
                "event_name"  => $event_name,
                "group_name"  => $group_name,
                "nid"         => $faker->randomNumber(2),
                "rsvp_count"  => $rsvp_count,
                "status"      => "upcoming",
                "tags"        => "",
                "time"        => "2021-08-18T16:00:00Z",
                "url"         => $url,
                "uuid"        => $uuid,
                "venue"       => $venue,
                "localtime"   => "2021-08-18T16:00:00.000000Z",
            ],
        ],
    ];
});
