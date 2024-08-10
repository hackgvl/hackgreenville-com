<?php

namespace Database\Factories;

use App\Enums\OrganizationStatus;
use App\Models\Category;
use App\Models\Org;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrgFactory extends Factory
{
    protected $model = Org::class;

    public function definition(): array
    {
        return [
            'uri' => $this->faker->word(),
            'title' => $this->faker->word(),
            'path' => $this->faker->url(),
            'city' => $this->faker->city(),
            'slug' => $this->faker->slug(),
            'focus_area' => $this->faker->word(),
            'primary_contact_person' => $this->faker->word(),
            'organization_type' => $this->faker->word(),
            'event_calendar_uri' => $this->faker->url(),
            'established_at' => now(),
            'status' => OrganizationStatus::Active,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'category_id' => Category::factory(),
        ];
    }
}
