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

    // external signing related routes
    Route::get('/externalSign/{document}', 'ExternalSignController@index');
    Route::get('/externalSign/{document}/{domain}/{language}', 'ExternalSignController@show');
    Route::post('/externalSign', 'ExternalSignController@store');

    // document provider related routes
    Route::get('/provider', 'DocumentProviderController@index');
    Route::get('/provider/usage', 'DocumentProviderController@show');
    Route::post('/provider', 'DocumentProviderController@store');
    Route::patch('/provider', 'DocumentProviderController@update');

    Route::get('/provider/certificate', 'DocumentProviderCertificateController');

    // document job related routes
    Route::get('/job/{job}', 'DocumentJobController@show');
    Route::post('/job', 'DocumentJobController@store');

    // document file related routes
    Route::get('/file/{document}', 'DocumentFileController@show');
    Route::post('/file', 'DocumentFileController@store');

    // document related routes
    Route::get('/document/{job}', 'DocumentController@index');
    Route::get('/document/{document}/{signeeRef}', 'DocumentController@show');
    Route::post('/document', 'DocumentController@store');
    Route::patch('/document/{document}', 'DocumentController@update');
    Route::delete('/document/{document}', 'DocumentController@destroy');
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
