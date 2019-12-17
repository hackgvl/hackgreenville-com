<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Route::get('/calendar', 'CalendarController@index')->name('calendar.index');
Route::get('/events', 'EventsController@index')->name('events.index');
Route::get('/orgs', 'OrgsController@index')->name('orgs.index');
Route::get('/about', 'HomeController@about')->name('about');

Route::get('/join-slack', 'SlackController@join');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/styles', 'StyleController@index')->name('styles.index');
