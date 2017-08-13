<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // Route::get();
});

Route::prefix('user')->group(function () {
    // Route::get();
});

Route::prefix('guest')->group(function () {
    Route::post('/login', 'RequestIdController@store');
});

Route::prefix('bidder')->group(function () {
    Route::get('/check/{request}', 'RequestIdController@show')->where(['request' => '[A-Za-z0-9]+']);
    Route::delete('/logout', 'RequestIdController@destroy');
});
