<?php

use HackGreenville\Api\Http\Controllers\EventApiV0Controller;
use HackGreenville\Api\Http\Controllers\EventApiV1Controller;
use HackGreenville\Api\Http\Controllers\MapLayerGeoJsonController;
use HackGreenville\Api\Http\Controllers\MapLayersApiV1Controller;
use HackGreenville\Api\Http\Controllers\OrgsApiV0Controller;
use HackGreenville\Api\Http\Controllers\OrgsApiV1Controller;

Route::middleware('api')
    ->prefix('api/')
    ->group(function () {
        Route::get('v0/events', EventApiV0Controller::class)->name('api.v0.events.index');
        Route::get('v0/orgs', OrgsApiV0Controller::class)->name('api.v0.orgs.index');

        Route::get('v1/events', EventApiV1Controller::class)->name('api.v1.events.index');
        Route::get('v1/organizations', OrgsApiV1Controller::class)->name('api.v1.organizations.index');
        Route::get('v1/map-layers', MapLayersApiV1Controller::class)->name('api.v1.map-layers.index');
        Route::get('v1/map-layers/{mapLayer:slug}/geojson', MapLayerGeoJsonController::class)->name('api.v1.map-layers.geojson');
    });
