<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\Venue;
use Exception;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    public function run()
    {
        // Get the South Carolina state record
        $southCarolina = State::where('abbr', 'SC')->first();

        if ( ! $southCarolina) {
            throw new Exception('South Carolina state not found. Please run StatesSeeder first.');
        }

        $venues = [
            [
                'slug' => 'synergy-mill',
                'name' => 'Synergy Mill',
                'address' => '400 Birnie St',
                'zipcode' => '29605',
                'city' => 'Greenville',
                'state_id' => $southCarolina->id,
                'country' => 'US',
                'lat' => '34.8361',
                'lng' => '-82.3643',
            ],
            [
                'slug' => 'openworks',
                'name' => 'OpenWorks',
                'address' => '101 N Main St #302',
                'zipcode' => '29601',
                'phone' => null,
                'city' => 'Greenville',
                'state_id' => $southCarolina->id,
                'country' => 'US',
                'lat' => '34.852020263672',
                'lng' => '-82.399681091309',
            ],
        ];

        foreach ($venues as $venue) {
            Venue::updateOrCreate(
                ['slug' => $venue['slug']],
                $venue
            );
        }
    }
}
