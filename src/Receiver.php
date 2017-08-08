<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class Receiver
{
    /** @var \Guzzle\HttpClient */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Receiver';

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
     * Get a specific or all receivers.
     *
     * @param  string      $provider
     * @param  string|null $receiver
     * @return json
     */
    public function get(string $provider, string $receiver = null)
    {
        // make the URL for this request
        $url = $this->makeUrl('GET', $provider, $receiver);

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
     * Create a receiver.
     *
     * @param  array  $receiver
     * @return json
     */
    public function create(array $receiver)
    {
        // make the URL for this request
        $url = self::URI;

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, $receiver);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $receiver
        ]);

        // return the response
        return $response;
    }

    /**
     * Create many receivers.
     *
     * @param  array  $receivers
     * @return json
     */
    public function createMany(array $receivers)
    {
        // instantiate empty responses array
        $responses = [];

        // loop through the receivers to create them all
        foreach ($receivers as $receiver) {
            $responses[] = $this->create($receiver);
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
    public function delete(string $provider, string $receiver)
    {
        // make the URL for this request
        $url = $this->makeUrl('DELETE', $provider, $receiver);

        // get the headers for this request
        $headers = $this->headers->make('DELETE', $url);

        // get the response
        $response = $this->client->delete($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Delete many receivers.
     *
     * @param  string $provider
     * @param  array  $receivers
     * @return json
     */
    public function deleteMany(string $provider, array $receivers)
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
    public function deleteAll(string $provider)
    {
        // make the URL for this request
        $url = $this->makeUrl('DELETE', $provider);

        // get the headers for this request
        $headers = $this->headers->make('DELETE', $url);

        // get the response
        $response = $this->client->delete($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
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
