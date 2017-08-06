<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

/**
 * Implementation for
 * https://api.signere.no/Documentation#Receiver
 */
class Receiver
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Receiver';

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
     * Get a specific or all receivers.
     *
     * @param  string      $provider
     * @param  string|null $receiver
     * @return json
     */
    public static function get(string $provider, string $receiver = null)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = $this->makeUrl('GET', $provider, $receiver);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Create a receiver.
     *
     * @param  string $provider
     * @param  array  $receiver
     * @return json
     */
    public static function create(string $provider, array $receiver)
    {
        // get the headers for this request
        $headers = Headers::make('POST');

        // make the URL for this request
        $url = $this->makeUrl('POST', $provider);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $receiver
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Create many receivers.
     *
     * @param  string $provider
     * @param  array  $receivers
     * @return json
     */
    public static function createMany(string $provider, array $receivers)
    {
        // instantiate empty responses array
        $responses = [];

        // loop through the receivers to create them all
        foreach ($receivers as $receiver) {
            $responses[] = $this->create($provider, $receiver);
        }

        // return them all
        return $responses;
    }

    /**
     * Delete a receiver.
     *
     * @param  string $provider
     * @param  string $receiver
     * @return json
     */
    public static function delete(string $provider, string $receiver)
    {
        // get the headers for this request
        $headers = Headers::make('DELETE');

        // make the URL for this request
        $url = $this->makeUrl('DELETE', $provider, $receiver);

        // get the response
        $response = $this->client->delete($url, [
            'headers' => $headers
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Delete many receivers.
     *
     * @param  string $provider
     * @param  array  $receivers
     * @return json
     */
    public static function deleteMany(string $provider, array $receivers)
    {
        // instantiate empty responses array
        $responses = [];

        // loop through the receivers to create them all
        foreach ($receivers as $receiver) {
            $responses[] = $this->delete($provider, $receiver);
        }

        // return them all
        return $responses;
    }

    /**
     * Delete all receivers.
     *
     * @param  string $provider
     * @return json
     */
    public static function deleteAll(string $provider)
    {
        // get the headers for this request
        $headers = Headers::make('DELETE');

        // make the URL for this request
        $url = $this->makeUrl('DELETE', $provider);

        // get the response
        $response = $this->client->delete($url, [
            'headers' => $headers
        ]);

        // return the json encoded response
        return $response->getBody()->getContents();
    }

    /**
     * Generate the url for different types of requests.
     *
     * @param  string      $reqType
     * @param  string      $provider
     * @param  string|null $receiver
     * @return string
     */
    private function makeUrl(string $reqType, string $provider, string $receiver = null)
    {
        // GET Requests
        if ($reqType === 'GET') {
            if (is_null($receiver)) {
                return sprintf('%s?ProviderId=%s', self::URI, $provider);
            }

            return sprintf('%s/%s?ProviderId=%s', self::URI, $receiver, $provider);
        }

        // POST Requests
        if ($reqType === 'POST') {
            return self::URI;
        }

        // DELETE Requests
        if ($reqType === 'DELETE') {
            if (is_null($receiver)) {
                return sprintf('%s/%s', self::URI, $provider);
            }

            return sprintf('%s/%s/%s', self::URI, $provider, $receiver);
        }
    }
}
