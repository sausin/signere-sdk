<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

/**
 * Implementation for
 * https://api.signere.no/Home/Details?controllername=Message&actionname=GetMessageForDocument
 */
class Message
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Message/Document/';

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
     * Get a list of messages sent for the given document ID
     *
     * @param  string $id
     * @return json
     */
    public static function get(string $id)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = self::URI . $id;

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }
}
