<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to Micro Service Boilerplate!'
    ]);
});

Route::group(['prefix' => 'api'], function () {
    Route::post('/register', 'UserController@store');
    Route::get('/', 'UserController@index');
});

Route::group([

    'middleware' => 'auth:api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

// Route::middleware('auth:api')->prefix('api')->group(function () {
//     Route::post('/login', 'AuthController@login');
//     Route::post('/logout', 'AuthController@logout');
//     Route::post('/refresh', 'AuthController@refresh');
//     Route::post('/me', 'AuthController@me');
// });
