<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class ExternalSign
{
    /** @var \Guzzle\HttpClient */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/externalsign';

    /**
     * Instantiate the class.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client, Headers $headers)
    {
        $this->client = $client;
        $this->headers = $headers;
    }

    /**
     * Return the login information from the login.
     *
     * @param  string $documentId
     * @return json
     */
    public function get(string $documentId)
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = $this->makeUrl('GET', $documentId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Get the URLs to a viewerapplet showing
     * documents in an iframe on website.
     *
     * @param  string $documentId
     * @param  array  $params
     * @return json
     */
    public function show(string $documentId, array $params)
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = $this->makeUrl('GET', $documentId, $params);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Get status of BankId mobile sign session.
     *
     * @param  string $signeeRefId
     * @return json
     */
    public function status(string $signeeRefId)
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = $this->makeUrl('GET', null, null, $signeeRefId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a externalsign request to integrate
     * signing of documents in a website.
     *
     * @param  array  $body
     * @return json
     */
    public function create(array $body)
    {
        // get the headers for this request
        $headers = $this->headers->make('POST');

        // make the URL for this request
        $url = $this->makeUrl('POST');

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a app launch uri for the BankID app.
     *
     * @param  array  $body
     * @return json
     */
    public function createApp(array $body)
    {
        // get the headers for this request
        $headers = $this->headers->make('PUT');

        // make the URL for this request
        $url = $this->makeUrl('PUT');

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Starts a BankID mobile sign session for the given document
     * with given mobilenumber and date of birth.
     *
     * @param  array  $body
     * @return json
     */
    public function startMobile(array $body)
    {
        // get the headers for this request
        $headers = $this->headers->make('PUT');

        // make the URL for this request
        $url = $this->makeUrl('PUT', $body['DocumentId']);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Generate the url for different types of requests.
     *
     * @param  string|null $documentId
     * @param  array       $params
     * @param  string|null $signeeRefId
     * @return string
     */
    private function makeUrl(string $documentId = null, array $params = [], string $signeeRefId = null)
    {
        // GET Requests
        if ($reqType === 'GET') {
            if (count($params) > 0) {
                return sprintf(
                    '%s/ViewerUrl/%s/%s/%s',
                    self::URI,
                    $documentId,
                    $params['Domain'],
                    $params['Language']
                );
            } elseif (! is_null($signeeRefId)) {
                return sprintf('%s/BankIDMobileSign/Status/{SigneeRefId}', self::URL, $signeeRefId);
            }

            return sprintf('%s/%s', self::URI, $documentId);
        }

        // POST Requests
        if ($reqType === 'POST') {
            return self::URI;
        }

        // PUT Requests
        if ($reqType === 'PUT') {
            if (is_null($documentId)) {
                return sprintf('%s/BankIDAppUrl', self::URI);
            }

            return sprintf('%s/BankIDMobileSign', self::URI);
        }
    }
}
