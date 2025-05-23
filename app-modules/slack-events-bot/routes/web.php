<?php

use HackGreenville\SlackEventsBot\Http\Controllers\SlackController;
use HackGreenville\SlackEventsBot\Http\Middleware\ValidateSlackRequest;
use Illuminate\Support\Facades\Route;

Route::prefix('slack')->group(function () {
    Route::get('/install', [SlackController::class, 'install'])->name('slack.install');
    Route::get('/auth', [SlackController::class, 'auth'])->name('slack.auth');
    Route::post('/events', [SlackController::class, 'events'])
        ->middleware(ValidateSlackRequest::class)
        ->name('slack.events');
});
