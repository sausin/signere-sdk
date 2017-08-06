<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class SignereId
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/SignereId';

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
     * Retrives a SignereID session to get the
     * information about the authorized user.
     *
     * @param  string $requestId
     * @param  bool   $metadata
     * @return json
     */
    public static function get(string $requestId, bool $metadata)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = $this->makeUrl('GET', $requestId, $metadata);

        // get the response
        $response = $this->client->delete($url, [
            'headers' => $headers
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Check if a SignereID session is completed or not.
     *
     * @param  string $requestId
     * @return json
     */
    public static function check(string $requestId)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = $this->makeUrl('GET', $requestId);

        // get the response
        $response = $this->client->delete($url, [
            'headers' => $headers
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Creates a SignereID request, and returns a url.
     *
     * @param  array  $body
     * @return string
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
     * Invalidates a SignereID request.
     *
     * @param  string $requestId
     * @return json
     */
    public static function delete(string $requestId)
    {
        // get the headers for this request
        $headers = Headers::make('POST');

        // make the URL for this request
        $url = $this->makeUrl('POST');

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => ['RequestId' => $requestId]
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Generate the url for different types of requests.
     * 
     * @param  string      $reqType
     * @param  string|null $requestId
     * @param  bool|null   $metadata
     * @return string
     */
    private function makeUrl(string $reqType, string $requestId = null, bool $metadata = null)
    {
        // GET Requests
        if ($reqType === 'GET') {
            if (is_null($metadata)) {
                return sprintf('%s/Completed/%s', self::URI, $requestId);
            }

            return sprintf('%s/%s?metadata=%s', self::URI, $requestId, $metadata);
        }

        // POST Requests
        if ($reqType === 'POST') {
            return self::URI;
        }

        // PUT Requests
        if ($reqType === 'PUT') {
            return sprintf('%s/Invalidate', self::URI, $provider, $receiver);
        }
    }
}
