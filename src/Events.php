<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class Events
{
    use AdjustUrl;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;
    
    /** @var string The environment this is being run in */
    protected $environment;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/events/encryptionkey';

    /**
     * Instantiate the class.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client, Headers $headers, $environment = null)
    {
        $this->client = $client;
        $this->headers = $headers;
        $this->environment = $environment;
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
        $headers = $this->headers->make('GET', $this->transformUrl($url));

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }
}
