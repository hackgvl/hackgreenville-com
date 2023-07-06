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
Route::get('/contact', 'ContactController@contact')->name('contact');
Route::post('/contact', 'ContactController@submit')->name('contact.submit');
Route::get('/events', 'EventsController@index')->name('events.index');
Route::get('/orgs', 'OrgsController@index')->name('orgs.index');
Route::get('/orgs/inactive', 'OrgsController@inactive')->name('orgs.inactive');
Route::get('/about', 'HomeController@about')->name('about');

Route::get('/join-slack', 'SlackController@join')->name('join-slack');
Route::post('/join-slack', 'SlackController@submit')->name('join-slack.submit');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/styles', 'StyleController@index')->name('styles.index');
Route::get('/style-guide', 'StyleController@styleGuide')->name('styles.styleGuide');
