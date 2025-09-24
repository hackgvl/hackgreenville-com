<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'id' => 1,
                'slug' => null,
                'label' => 'Inactive',
            ],
            [
                'id' => 2,
                'slug' => null,
                'label' => 'Meetup Groups',
            ],
            [
                'id' => 3,
                'slug' => null,
                'label' => 'Conferences / Festivals / Hackathons',
            ],
            [
                'id' => 4,
                'slug' => null,
                'label' => 'Code Schools',
            ],
            [
                'id' => 5,
                'slug' => null,
                'label' => 'Workforce Development',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}