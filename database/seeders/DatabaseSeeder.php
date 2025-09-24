<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(StatesSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(OrganizationSeeder::class);
        $this->call(VenueSeeder::class);
        $this->call(EventSeeder::class);
    }
}
