<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class Status
{
    /** @var \Guzzle\HttpClient */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Status';

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
     * Returns the UTC time of the server.
     *
     * @return Object
     */
    public function getServerTime()
    {
        // make the URL for this request
        $url = sprintf('%s/ServerTime', self::URI);

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Returns the status the server.
     *
     * @param  string $request
     * @return Object
     * @todo need to setup the PingToken
     */
    public function getServerStatus(string $request = 'test')
    {
        // make the URL for this request
        $url = sprintf('%s/Ping/%s', self::URI, $request);

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => array_merge(
                $headers,
                ['PingToken' => '']
            )
        ]);

        // return the response
        return $response;
    }
}
