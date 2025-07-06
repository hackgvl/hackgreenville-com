<?php

use HackGreenville\SlackEventsBot\Http\Controllers\SlackController;
use Illuminate\Support\Facades\Route;

Route::prefix('slack')->group(function () {
    Route::get('/install', [SlackController::class, 'install'])->name('slack.install');
    Route::get('/auth', [SlackController::class, 'auth'])->name('slack.auth');
});
