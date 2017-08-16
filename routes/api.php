<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('Admin')->group(function () {
    // events
    Route::get('/events', 'EventsController');

    // invoices
    Route::get('/invoice/{year}/{month}', 'InvoiceController');

    // for sending out messages to signeres
    Route::get('/document/{document}/messages', 'MessagesController@index');
    Route::get('messages/{message}', 'MessagesController@show');
    Route::post('messages', 'MessagesController@store');
    Route::patch('messages', 'MessagesController@update');

    // for external messages
    Route::post('externalMessage', 'ExternalMessagesController');

    // for renewal / recovery of keys
    Route::patch('keys/primary', 'PrimaryKeyRecoveryController@update');
    Route::post('keys/primary', 'PrimaryKeyRecoveryController@store');
    Route::post('keys/primary/renew', 'PrimaryKeyRenewalController');
    Route::post('keys/secondary/renew', 'SecondaryKeyRenewalController');

    // receiver routes
    Route::get('receivers', 'ReceiverController@index');
    Route::get('receivers/{receiver}', 'ReceiverController@show');
    Route::post('receivers', 'ReceiverController@store');
    Route::delete('receivers', 'ReceiverController@destroy');

    // statistics route
    Route::post('statistics', 'StatisticsController');

    // status routes
    Route::get('status', 'StatusController@index');
    Route::get('status/{message}', 'StatusController@show');

    // request id check
    Route::post('/requestDetails', 'RequestIdController');

    // external signing related items
    Route::get('/externalSign/{document}', 'ExternalSignController@index');
    Route::get('/externalSign/{document}/{domain}/{language}', 'ExternalSignController@show');
    Route::post('/externalSign', 'ExternalSignController@store');
});

Route::prefix('user')->group(function () {
});

Route::prefix('guest')->group(function () {
    Route::post('/login', 'RequestIdController@store');

    // mobile signing status
    Route::get('/mobile/externalSign/status/{signeeRef}', 'MobileExternalSignController@show');
    Route::post('/mobile/externalSign/start', 'MobileExternalSignController@store');
});

Route::prefix('bidder')->group(function () {
    Route::get('/check/{request}', 'RequestIdController@show')->where(['request' => '[A-Za-z0-9]+']);
    Route::delete('/logout', 'ExternalLoginController');
});
