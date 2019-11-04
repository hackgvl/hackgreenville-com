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

Route::middleware('auth:api')->get('/user', 'UserController@me');

Route::apiResource('carousel', 'CarouselController');
Route::post('carousel/{carousel}/images', 'CarouselController@upload_image');

Route::get('/orgs', 'apiController@showOrgs');
Route::get('/events', 'apiController@showEvents');
