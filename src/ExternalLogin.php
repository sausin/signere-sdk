<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class ExternalLogin
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/ExternalLogin';

    /**
     * Instantiate the class.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Return the login information from the login.
     *
     * @param  string $requestId
     * @return json
     */
    public static function get(string $requestId)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = $this->makeUrl('GET', $requestId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Creates a app login response which contains
     * the launchuri to launch the BankID app.
     *
     * @param  array  $body
     * @return json
     */
    public static function create(array $body)
    {
        // get the headers for this request
        $headers = Headers::make('POST');

        // make the URL for this request
        $url = $this->makeUrl('POST');

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Creates a BankID mobile login response.
     *
     * @param  array  $body
     * @return json
     */
    public static function createMobile(array $body)
    {
        // get the headers for this request
        $headers = Headers::make('POST');

        // make the URL for this request
        $url = $this->makeUrl('POST', null, true);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Starts the BankID mobile session that
     * was created by the 'create' method.
     *
     * @param  string|null $requestId
     * @return json
     */
    public static function startMobile(string $requestId = null)
    {
        // get the headers for this request
        $headers = Headers::make('POST');

        // make the URL for this request
        $url = $this->makeUrl('POST', null, true, true);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => ['RequestId' => $requestId]
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Invalidate the login request to prevent
     * any replay attacks.
     *
     * @param  string $requestId
     * @return json
     */
    public static function delete(string $requestId)
    {
        // get the headers for this request
        $headers = Headers::make('PUT');

        // make the URL for this request
        $url = $this->makeUrl('PUT');

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => ['RequestId' => $requestId]
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Alias method for delete.
     *
     * @param  string $requestId
     * @return json
     */
    public static function destroy(string $requestId)
    {
        return $this->delete($requestId);
    }

    /**
     * Generate the url for different types of requests.
     *
     * @param  string|null $requestId
     * @param  bool|null   $mobile
     * @param  bool|null   $start
     * @return string
     */
    private function makeUrl(string $requestId = null, bool $mobile = null, bool $start = null)
    {
        // GET Requests
        if ($reqType === 'GET') {
            return sprintf('%s/%s', self::URI, $requestId);
        }

        // POST Requests
        if ($reqType === 'POST') {
            if ($mobile && $start) {
                return sprintf('%s/BankIDMobileLogin/Start', self::URI);
            } elseif ($mobile) {
                return sprintf('%s/BankIDMobileLogin/Create', self::URI);
            }

            return sprintf('%s/AppLogin', self::URI);
        }

        // PUT Requests
        if ($reqType === 'PUT') {
            return sprintf('%s/InvalidateLogin', self::URI);
        }
    }
}
