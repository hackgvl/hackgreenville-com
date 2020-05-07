<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', 'UserController@me')->name('me');

Route::get('/calendar', 'CalendarController@index');
Route::get('/orgs', 'apiController@showOrgs')->name('orgs');
Route::get('/events', 'apiController@showEvents')->name('events');
Route::get('/homepage/events', 'HomepageController@events')->name('homepage.timeline');
