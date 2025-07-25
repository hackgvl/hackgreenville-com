<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CalendarFeedController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\HGNightsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabsController;
use App\Http\Controllers\OrgsController;
use App\Http\Controllers\SlackController;
use App\Http\Controllers\StyleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/data', [CalendarController::class, 'data'])->name('calendar.data');

Route::get('/calendar-feed', [CalendarFeedController::class, 'index'])->name('calendar-feed.index');
Route::get('/calendar-feed.ics', [CalendarFeedController::class, 'show'])->name('calendar-feed.show');

Route::get('/events', [EventsController::class, 'index'])->name('events.index');
Route::get('/labs', [LabsController::class, 'index'])->name('labs.index');
Route::get('/hg-nights', [HGNightsController::class, 'index'])->name('hg-nights.index');
Route::get('/contribute', fn () => view('contribute'))->name('contribute');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/code-of-conduct', fn () => view('code-of-conduct'))->name('code-of-conduct');

Route::get('/orgs', [OrgsController::class, 'index'])->name('orgs.index');
Route::get('/orgs/{org:slug}', [OrgsController::class, 'show'])->name('orgs.show');
Route::get('/orgs/inactive', [OrgsController::class, 'inactive'])->name('orgs.inactive');

Route::get('/contact', [ContactController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/join-slack', [SlackController::class, 'join'])->name('join-slack');
Route::post('/join-slack', [SlackController::class, 'submit'])->name('join-slack.submit');

Route::get('/styles', [StyleController::class, 'index'])->name('styles.index');

// Redirects
Route::redirect('/nights', '/hg-nights', 301);
Route::redirect('/give', '/contribute', 301);
