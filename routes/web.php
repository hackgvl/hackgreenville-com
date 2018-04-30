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

// Route::get('/', function () {
//   return view('welcome');
// });

Route::get('/', 'apiController@debug');
Route::get('/orgs', 'apiController@showOrgs');
Route::get('/events', 'apiController@showEvents');

/**
 * Example routes for Now UI Kit pages.
 */

Route::get('/test', function () {
  return view('index');
});

Route::get('/landing', function () {
  return view('landing-page');
});

Route::get('/login', function () {
  return view('login-page');
});

Route::get('/icons', function () {
  return view('nucleo-icons');
});

Route::get('/profile', function () {
  return view('profile-page');
});

Route::get('/template', function () {
  return view('template');
});

Route::get('/tutorial', function () {
  return view('tutorial-components');
});