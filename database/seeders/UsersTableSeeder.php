<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'first_name' => 'admin',
                'last_name'  => 'admin',
                'password'   => bcrypt('admin'),
                'active' => true,
            ]
        );
    }
}
