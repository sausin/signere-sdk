<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Signere API Mode of Operation
    |--------------------------------------------------------------------------
    |
    | If the package is being used in test mode, set this to 'test'. If
    | your laravel package is in local environment then this package
    | automatically defaults to testing. In this mode all calls
    | to signere api are made to the test urls.  This can be
    | overridden by setting this variable to 'production'.
    |
    */

    'mode'              => null,

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
    | Sign Status URLs
    |--------------------------------------------------------------------------
    |
    | The URLs corresponding to the callbacks from Signere based on the
    | response condition. Details regarding which url is needed for
    | which case. See:  http://bit.ly/2wU8ihX
    |
    */

    'sign_cancel_url'   => 'https://abc.com/auth/abort?requestid=[1]&externalid=[2]',
    'sign_error_url'    => 'https://abc.com/auth/error?status=[0]',
    'sign_success_url'  => 'https://abc.com/auth/success?requestid=[1]&externalid=[2]',

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

    /*
    |--------------------------------------------------------------------------
    | Hashing Algorithm
    |--------------------------------------------------------------------------
    |
    | The value below is used when generating the headers. This should either
    | be sha256 (default) or sha512. There is normally no need to change
    | this but can be done if required.
    |
    */

    'hash_algorithm'    => 'sha256',

    /*
    |--------------------------------------------------------------------------
    | iframe Parameters
    |--------------------------------------------------------------------------
    |
    | The domain parameter is required when the remote login or sign is
    | required to be displayed inside an iframe. The height parameter
    | gives a default value to the iframe height. This is normally
    | recommended to be sent into the request.
    |
    */

    'domain'            => 'domain.dev',
    'iframe_height'     => 400,
];
