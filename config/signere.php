<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Signere API Credentials
    |--------------------------------------------------------------------------
    |
    | The credentials obtained from Signere go here. This package utilized
    | the information given here to authenticate with the Signere API &
    | provide the functionality corresponding to the various paths.
    |
    */
   
    'id'                => env('SIGNERE_API_ID', 'id'),
    'primary_key'       => env('SIGNERE_KEY_PRIMARY', 'primary_key'),
    'secondary_key'     => env('SIGNERE_KEY_SECONDARY', 'secondary_key'),
    'ping_token'        => env('PINGTOKEN', 'ping_token'),

    /*
    |--------------------------------------------------------------------------
    | Status URLs
    |--------------------------------------------------------------------------
    |
    | The URLs corresponding to the callbacks from Signere based on the
    | response condition. Details regarding which url is needed for
    | which case. See:  http://bit.ly/2wU8ihX
    |
    */
   
    'cancel_url'        => 'https://abc.com/auth/abort?requestid=[1]&externalid=[2]',
    'error_url'         => 'https://abc.com/auth/error?status=[0]',
    'success_url'       => 'https://abc.com/auth/success?requestid=[1]&externalid=[2]',

    /*
    |--------------------------------------------------------------------------
    | Identity Provider
    |--------------------------------------------------------------------------
    |
    | The identity provider service to be used with the package. The default
    | value below is the provider in Norway. See: http://bit.ly/2wU8ihX
    |
    */
   
    'identity_provider' => 'NO_BANKID_WEB',
];
