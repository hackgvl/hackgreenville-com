<?php

namespace Database\Factories;

use App\Models\MapLayer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MapLayerFactory extends Factory
{
    protected $model = MapLayer::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->sentence(),
            'center_latitude' => $this->faker->latitude(34.0, 35.0),
            'center_longitude' => $this->faker->longitude(-83.0, -82.0),
            'zoom_level' => $this->faker->numberBetween(1, 20),
            'geojson_link' => $this->faker->optional()->url(),
            'contribute_link' => $this->faker->optional()->url(),
            'raw_data_link' => $this->faker->optional()->url(),
            'maintainers' => [['name' => $this->faker->name()]],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function forDocumentation()
    {
        return $this->state(function (array $attributes) {
            $faker = \Faker\Factory::create();
            $faker->seed(1234);

            return [
                'title' => $faker->words(3, true),
                'slug' => $faker->slug,
                'description' => $faker->sentence(),
                'center_latitude' => 34.850700,
                'center_longitude' => -82.398500,
                'zoom_level' => 10,
                'geojson_link' => $faker->url(),
                'contribute_link' => $faker->url(),
                'raw_data_link' => $faker->url(),
                'maintainers' => [['name' => $faker->name()]],
                'created_at' => '2025-01-01T12:00:00.000000Z',
                'updated_at' => '2025-01-01T12:00:00.000000Z',
            ];
        });
    }
}
