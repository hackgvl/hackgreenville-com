<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Org;
use Illuminate\Database\Seeder;
use Random\RandomException;

class OrgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws RandomException
     */
    public function run(): void
    {
        Org::factory(random_int(10, 20))
            ->hasTags(random_int(1, 5))
            ->create()
            ->each(function ($org) {
                // Recycle or create a category
                $category = Category::inRandomOrder()->first() ?? Category::factory()->create();
                $org->category()->associate($category);

                // Recycle or create events
                $events = Event::inRandomOrder()->limit(random_int(1, 10))->get();

                // If there are not enough existing events, create new ones
                if ($events->count() < random_int(1, 10)) {
                    $newEvents = Event::factory(random_int(1, 10) - $events->count())->create();
                    $events = $events->merge($newEvents);
                }

                // Attach events to the organization
                $org->events()->saveMany($events);

                $org->save();
            });
    }
}
