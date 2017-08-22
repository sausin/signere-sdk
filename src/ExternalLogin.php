<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;
use BadMethodCallException;
use UnexpectedValueException;

class ExternalLogin
{
    use AdjustUrl;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** @var string The environment this is being run in */
    protected $environment;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/ExternalLogin';

    /**
     * Instantiate the class.
     *
     * @param Client  $client
     * @param Headers $headers
     * @param string  $environment
     */
    public function __construct(Client $client, Headers $headers, $environment = null)
    {
        $this->client = $client;
        $this->headers = $headers;
        $this->environment = $environment;
    }

    /**
     * Return the login information from the login.
     *
     * @param  string $requestId
     * @return object
     */
    public function getLoginInfo(string $requestId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/%s', self::URI, $requestId));

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a app login response which contains
     * the launchuri to launch the BankID app.
     *
     * @param  array  $body
     * @return object
     */
    public function createAppLaunchUri(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['ExternalId', 'ReturnUrl'];

        // if the body doesn't have needed fields, throw an exception
        if (! array_has_all_keys($body, $needKeys)) {
            throw new BadMethodCallException(
                'Missing fields in input array. Need '.implode(', ', $needKeys)
            );
        }

        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/AppLogin', self::URI));

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, $body);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a BankID mobile login response.
     *
     * @param  array  $body
     * @return object
     */
    public function createMobile(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['DateOfBirth', 'Mobile'];

        // if the body doesn't have needed fields, throw an exception
        if (! array_has_all_keys($body, $needKeys)) {
            throw new BadMethodCallException(
                'Missing fields in input array. Need '.implode(', ', $needKeys)
            );
        }

        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/BankIDMobileLogin/Create', self::URI));

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, $body);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }

    /**
     * Starts the BankID mobile session that
     * was created by the 'create' method.
     *
     * @param  array  $body
     * @return object
     */
    public function startMobileSession(array $body = [])
    {
        // let the user know that if he is sending some data
        // it should be RequestId. Nothing else goes here.
        if (! empty($body) && ! isset($body['RequestId'])) {
            throw new UnexpectedValueException('Input should only have RequestId');
        }

        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/BankIDMobileLogin/Start', self::URI));

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, $body);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }

    /**
     * Invalidate the login request to prevent
     * any replay attacks.
     *
     * @param  array  $body
     * @return object
     */
    public function invalidateLogin(array $body)
    {
        // let the user know that if he is sending some data
        // it should be RequestId. Nothing else goes here.
        if (! isset($body['RequestId'])) {
            throw new BadMethodCallException('Input should have RequestId');
        } elseif (count($body) > 1) {
            throw new UnexpectedValueException('Input should only have RequestId');
        }

        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/InvalidateLogin', self::URI));

        // get the headers for this request
        $headers = $this->headers->make('PUT', $url, $body);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }
}
