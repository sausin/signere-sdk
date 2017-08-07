<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class Message
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Message';

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
     * Get a list of messages sent for the given document ID.
     *
     * @param  string $messageId
     * @return json
     */
    public static function get(string $messageId)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = sprintf('%s/%s', self::URI, $messageId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Retrieves a list of all the messages that 
     * are sent for the given document.
     * 
     * @param  string $documentId
     * @return Object
     */
    public static function all(string $documentId)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = sprintf('%s/Document/%s', self::URI, $documentId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Sends a message to the signees of a given document.
     * 
     * @param  array  $body
     * @return Object
     */
    public static function sendMessage(array $body)
    {
        // get the headers for this request
        $headers = Headers::make('POST');

        // make the URL for this request
        $url = sprintf('%s/Message', self::URI);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Sends a new message to the Signeeref if the first one failed.
     * 
     * @param  array  $body
     * @return Object
     */
    public static function sendNewMessage(array $body)
    {
        // get the headers for this request
        $headers = Headers::make('PUT');

        // make the URL for this request
        $url = sprintf('%s/Message/SendNewDocumentMessage', self::URI);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Sends a message to an external person with a link/URL
     * to view a document.
     * 
     * @param  array  $body
     * @return Object
     */
    public static function sendExternalMessage(array $body)
    {
        // get the headers for this request
        $headers = Headers::make('POST');

        // make the URL for this request
        $url = sprintf('%s/Message/SendExternalMessage', self::URI);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }
}
