<?php

namespace Sausin\Signere;

class Status
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Status';

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
     * Returns the UTC time of the server.
     *
     * @return Object
     */
    public static function getServerTime()
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = sprintf('%s/ServerTime', self::URI);

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
     */
    public static function getServerStatus(string $request = 'test')
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = sprintf('%s/Ping/%s', self::URI, $request);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }
}
