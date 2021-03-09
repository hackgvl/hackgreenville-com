<?php

namespace Database\Factories;

use App\Models\Event;
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
                'event_name'   => $event_name = $this->faker->name . ' tech talk',
                'group_name'   => $group_name = $this->faker->name . ' tech group',
                'description'  => $description = $this->faker->text(100),
                'rsvp_count'   => $rsvp_count = $this->faker->randomNumber(2),
                'active_at'    => $active_at = $this->faker->dateTime('+2 hours'),
                'uri'          => $url = $this->faker->url,
                'venue_id'     => $venue,
                'event_uuid'   => $uuid = $this->faker->uuid,
                'expire_at'    => $expire_at = $this->faker->dateTimeBetween($active_at, '+2 days'),
                'cancelled_at' => null,

                'cache' => [
                        [
                                "created_at"  => "2019-12-09T21:24:53Z",
                                "data_as_of"  => "2020-09-09T18:40:10Z",
                                "description" => $description,
                                "event_name"  => $event_name,
                                "group_name"  => $group_name,
                                "nid"         => $this->faker->randomNumber(2),
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
    }
}
