<?php

use HackGreenville\SlackEventsBot\Http\Controllers\SlackController;
use HackGreenville\SlackEventsBot\Http\Middleware\ValidateSlackRequest;
use Illuminate\Support\Facades\Route;

Route::post('events', [SlackController::class, 'events'])
    ->middleware(ValidateSlackRequest::class)
    ->name('events');
