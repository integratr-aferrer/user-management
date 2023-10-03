<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to Micro Service Boilerplate!'
    ]);
});

Route::group(['prefix' => 'api'], function () {
    Route::post('/register', 'UserController@store');
});
