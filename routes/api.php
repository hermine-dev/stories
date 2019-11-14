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

Route::namespace('Api')->group(function () {
    Route::get('/stories', 'StoriesController@index');
    Route::get('/stories/{id}', 'StoriesController@show');
    Route::post('/stories/save', 'StoriesController@save');
    Route::delete('/stories/removeItem/{id}', 'StoriesController@removeItem');
});
