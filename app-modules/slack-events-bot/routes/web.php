<?php

use HackGreenville\SlackEventsBot\Http\Controllers\SlackController;
use Illuminate\Support\Facades\Route;

Route::get('/install', [SlackController::class, 'install'])->name('install');
Route::get('/auth', [SlackController::class, 'auth'])->name('auth');
