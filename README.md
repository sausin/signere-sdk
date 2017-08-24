# Signere SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sausin/signere-sdk.svg?style=flat-square)](https://packagist.org/packages/sausin/signere-sdk)
[![Build Status](https://img.shields.io/travis/sausin/signere-sdk/master.svg?style=flat-square)](https://travis-ci.org/sausin/signere-sdk)
[![Quality Score](https://img.shields.io/scrutinizer/g/sausin/signere-sdk.svg?style=flat-square)](https://scrutinizer-ci.com/g/sausin/signere-sdk)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/sausin/signere-sdk.svg?style=flat-square)](https://scrutinizer-ci.com/g/sausin/signere-sdk)
[![StyleCI](https://styleci.io/repos/100803677/shield?branch=master)](https://styleci.io/repos/100803677)
[![Total Downloads](https://img.shields.io/packagist/dt/sausin/signere-sdk.svg?style=flat-square)](https://packagist.org/packages/sausin/signere-sdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](https://opensource.org/licenses/MIT)


[Signere](https://www.signere.no/) provides APIs to perform authentication and signing of documents using various identification systems.

This package uses the publicly available [API documentation](https://api.signere.no/Documentation) provided by Signere and makes it easy to work with those APIs.

While it's created to work well with Laravel, it's possible to use the functionality of the package with any setup in PHP. An implementation of `Illuminate\Contracts\Config\Repository` is required in non-laravel environments to provide the configuration inputs for the package.

Laravel handles dependency injection using its powerful service container. If this package is used without Laravel, your application needs to manage the dependency injection on its own and inject the relevant classes.

Note that the controllers, service provider, routes and resources can only be used with Laravel.

## Installing

Install via composer:
```sh
composer require sausin/signere-sdk
```

When using the package with Laravel 5.5 and upwards, the service provider will automatically be registered. For Laravel 5.4, the service provider needs to be registered in the `config/app.php` file as below:

```php
Sausin\Signere\SignereServiceProvider::class
```

The config file should be published using the below artisan command:

```sh
php artisan vendor:publish --provider="Sausin\Signere\SignereServiceProvider"
```

The config file has the following keys setup for the package to function correctly:

```php
'mode'              => null,
'id'                => env('SIGNERE_API_ID', 'id'),
'primary_key'       => env('SIGNERE_KEY_PRIMARY', 'primary_key'),
'secondary_key'     => env('SIGNERE_KEY_SECONDARY', 'secondary_key'),
'ping_token'        => env('PINGTOKEN', 'ping_token'),
'cancel_url'        => 'https://abc.com/auth/abort?requestid=[1]&externalid=[2]',
'error_url'         => 'https://abc.com/auth/error?status=[0]',
'success_url'       => 'https://abc.com/auth/success?requestid=[1]&externalid=[2]',
'sign_cancel_url'   => 'https://abc.com/auth/abort?requestid=[1]&externalid=[2]',
'sign_error_url'    => 'https://abc.com/auth/error?status=[0]',
'sign_success_url'  => 'https://abc.com/auth/success?requestid=[1]&externalid=[2]',
'identity_provider' => 'NO_BANKID_WEB',
```

The `.env` file in the project this SDK is used in should have the following keys defined:

```
SIGNERE_API_ID
SIGNERE_KEY_PRIMARY
SIGNERE_KEY_SECONDARY
PINGTOKEN
```

These keys are provided by Signere when you register yourself with them.

If you're using this package without Laravel, then the these variables need to be provided by an implementation of `Illuminate\Contracts\Config\Repository`. This is injected to the respective classes where required.

## Usage

To make API calls, appropriate headers need to be set to signere can verify your requests are authentic. This is automatically taken care of by the `Header` class in this package. The specifications in Signere documentation for required headers in API calls have been used.

### Access Control with Laravel

If the package is used with Laravel, routes are automatically registered. Refer to the `routes/api.php` in this package to see all the available routes.

The routes are split into four groups - `admin`, `user`, `guest` and `bidder`. Access control is made possible by the `Signere` class which can be setup in the `AppServiceProvider` of the application using this package. An example is shown below:

```php
Signere::auth(function ($request) {
    // return true / false;
});
```

Each controller's constructor method calls `Authenticate` middleware, which in turn calls the `Signere` class to check if the request is to be authenticated.

Most of the routes are organized under `admin` group as there is a cost to the operations performed.

### Utilizing Authentication Services

The workflow for authentication is like this:

* Create a request using the `create` method on `RequestId` class. This method accepts an array of values and returns a `GuzzleHttp\Psr7\Response` object, which contains among other things the URL for signing
* Go to the URL above and provide your BankID credentials. Depending on the outcome of this authentication process, you will be redirected to one of the URLs given to the `create` method above
* When you want to logout or invalidate your login, use the `invalidate` method on `RequestId` class. This accepts a string input which corresponds to the `requestid` received on the response of the `create` method

### Utilizing Signing Services

Signing can be performed in two ways:

* External
* Signere way

The first method means that you create a document available for signing and distribute the URLs to the signees yourself.

In the second method, Signere does the hard work for you. You basically setup a process with Signere which sends out the notifications to signees. It is also possible to automate reminders.

...
...

## Credits
* Taylor Otwell and Mohamed Said for the awesome [Horizon](https://github.com/laravel/horizon) package which served as a template for this.
* Signere for providing an amazing API service to use BankId

## Trademarks
All trademarks belong to their respective owners