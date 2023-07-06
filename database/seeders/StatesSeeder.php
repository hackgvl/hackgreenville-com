<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            ['name' => 'Alaska', 'abbr' => 'AK'],
            ['name' => 'Alabama', 'abbr' => 'AL'],
            ['name' => 'American Samoa', 'abbr' => 'AS'],
            ['name' => 'Arizona', 'abbr' => 'AZ'],
            ['name' => 'Arkansas', 'abbr' => 'AR'],
            ['name' => 'California', 'abbr' => 'CA'],
            ['name' => 'Colorado', 'abbr' => 'CO'],
            ['name' => 'Connecticut', 'abbr' => 'CT'],
            ['name' => 'Delaware', 'abbr' => 'DE'],
            ['name' => 'District of Columbia', 'abbr' => 'DC'],
            ['name' => 'Federated States of Micronesia', 'abbr' => 'FM'],
            ['name' => 'Florida', 'abbr' => 'FL'],
            ['name' => 'Georgia', 'abbr' => 'GA'],
            ['name' => 'Guam', 'abbr' => 'GU'],
            ['name' => 'Hawaii', 'abbr' => 'HI'],
            ['name' => 'Idaho', 'abbr' => 'ID'],
            ['name' => 'Illinois', 'abbr' => 'IL'],
            ['name' => 'Indiana', 'abbr' => 'IN'],
            ['name' => 'Iowa', 'abbr' => 'IA'],
            ['name' => 'Kansas', 'abbr' => 'KS'],
            ['name' => 'Kentucky', 'abbr' => 'KY'],
            ['name' => 'Louisiana', 'abbr' => 'LA'],
            ['name' => 'Maine', 'abbr' => 'ME'],
            ['name' => 'Marshall Islands', 'abbr' => 'MH'],
            ['name' => 'Maryland', 'abbr' => 'MD'],
            ['name' => 'Massachusetts', 'abbr' => 'MA'],
            ['name' => 'Michigan', 'abbr' => 'MI'],
            ['name' => 'Minnesota', 'abbr' => 'MN'],
            ['name' => 'Mississippi', 'abbr' => 'MS'],
            ['name' => 'Missouri', 'abbr' => 'MO'],
            ['name' => 'Montana', 'abbr' => 'MT'],
            ['name' => 'Nebraska', 'abbr' => 'NE'],
            ['name' => 'Nevada', 'abbr' => 'NV'],
            ['name' => 'New Hampshire', 'abbr' => 'NH'],
            ['name' => 'New Jersey', 'abbr' => 'NJ'],
            ['name' => 'New Mexico', 'abbr' => 'NM'],
            ['name' => 'New York', 'abbr' => 'NY'],
            ['name' => 'North Carolina', 'abbr' => 'NC'],
            ['name' => 'North Dakota', 'abbr' => 'ND'],
            ['name' => 'Northern Mariana Islands', 'abbr' => 'MP'],
            ['name' => 'Ohio', 'abbr' => 'OH'],
            ['name' => 'Oklahoma', 'abbr' => 'OK'],
            ['name' => 'Oregon', 'abbr' => 'OR'],
            ['name' => 'Palau', 'abbr' => 'PW'],
            ['name' => 'Pennsylvania', 'abbr' => 'PA'],
            ['name' => 'Puerto Rico', 'abbr' => 'PR'],
            ['name' => 'Rhode Island', 'abbr' => 'RI'],
            ['name' => 'South Carolina', 'abbr' => 'SC'],
            ['name' => 'South Dakota', 'abbr' => 'SD'],
            ['name' => 'Tennessee', 'abbr' => 'TN'],
            ['name' => 'Texas', 'abbr' => 'TX'],
            ['name' => 'Utah', 'abbr' => 'UT'],
            ['name' => 'Vermont', 'abbr' => 'VT'],
            ['name' => 'Virgin Islands', 'abbr' => 'VI'],
            ['name' => 'Virginia', 'abbr' => 'VA'],
            ['name' => 'Washington', 'abbr' => 'WA'],
            ['name' => 'West Virginia', 'abbr' => 'WV'],
            ['name' => 'Wisconsin', 'abbr' => 'WI'],
            ['name' => 'Wyoming', 'abbr' => 'WY'],
            ['name' => 'Armed Forces Africa', 'abbr' => 'AE'],
            ['name' => 'Armed Forces Americas (except Canada)', 'abbr' => 'AA'],
            ['name' => 'Armed Forces Canada', 'abbr' => 'AE'],
            ['name' => 'Armed Forces Europe', 'abbr' => 'AE'],
            ['name' => 'Armed Forces Middle East', 'abbr' => 'AE'],
            ['name' => 'Armed Forces Pacific', 'abbr' => 'AP'],
        ];

        foreach ($states as $state) {
            State::updateOrCreate(['abbr' => $state['abbr']], [
                'abbr' => $state['abbr'],
                'name' => $state['name'],
            ]);
        }
    }
}
