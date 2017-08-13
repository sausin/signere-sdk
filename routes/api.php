<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('Admin')->group(function () {
    Route::get('/invoice/{year}/{month}', 'InvoiceController');

    Route::get('/document/{document}/messages', 'MessagesController@index');
    Route::get('messages/{message}', 'MessagesController@show');
    Route::post('messages', 'MessagesController@store');
    Route::patch('messages', 'MessagesController@update');

    // for external messages
    Route::post('externalMessage', 'ExternalMessagesController');
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
