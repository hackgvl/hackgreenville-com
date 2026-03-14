<?php

use HackGreenville\SlackEventsBot\Http\Controllers\SlackController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'throttle:10,1'])
    ->prefix('slack')
    ->name('slack.')
    ->group(function () {
        Route::get('/install', [SlackController::class, 'install'])->name('install');
        Route::get('/auth', [SlackController::class, 'auth'])->name('auth');
    });
