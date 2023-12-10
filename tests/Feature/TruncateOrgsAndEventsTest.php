<?php

use App\Console\Commands\TruncateOrgsAndEvents;
use App\Models\Event;
use App\Models\Org;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('wipes the events and orgs tables', function () {
    $expected_count = 10;

    Event::factory()->count($expected_count)->create();

    // Check pre-wipe count
    expect(Event::count())->toEqual($expected_count);
    expect(Org::count())->toEqual($expected_count);


    Artisan::call(TruncateOrgsAndEvents::class);

    // Ensure tables are squeaky clean
    expect(Event::count())->toEqual(0);
    expect(Org::count())->toEqual(0);
});
