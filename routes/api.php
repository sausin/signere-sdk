<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get();
});

Route::prefix('user')->group(function () {
    Route::get();
});

Route::prefix('guest')->group(function () {
    Route::get();
});

Route::prefix('bidder')->group(function () {
    Route::get();
});
