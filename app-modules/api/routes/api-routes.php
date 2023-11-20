<?php

use HackGreenville\Api\Http\Controllers\EventApiV0Controller;
use HackGreenville\Api\Http\Controllers\OrgsApiV0Controller;

Route::middleware('api')
    ->prefix('api/')
    ->group(function () {
        Route::get('v0/events', EventApiV0Controller::class)->name('api.v0.events.index');
        Route::get('v0/orgs', OrgsApiV0Controller::class)->name('api.v0.orgs.index');
    });
