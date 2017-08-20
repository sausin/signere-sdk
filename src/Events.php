<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class Events
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/events/encryptionkey';

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
     * Returns the EventsQueue encryptionKey as a base64 encoded string.
     *
     * @return object
     */
    public function getEncryptionKey()
    {
        // make the URL for this request
        $url = self::URI;

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }
}
