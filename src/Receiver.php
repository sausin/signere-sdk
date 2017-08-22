<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class Receiver
{
    use AdjustUrl;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** @var string The environment this is being run in */
    protected $environment;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Receiver';

    /**
     * Instantiate the class.
     *
     * @param Client  $client
     * @param Headers $headers
     * @param string  $environment
     */
    public function __construct(Client $client, Headers $headers, $environment = null)
    {
        $this->client = $client;
        $this->headers = $headers;
        $this->environment = $environment;
    }

    /**
     * Get a specific or all receivers.
     *
     * @param  string      $provider
     * @param  string|null $receiver
     * @return object
     */
    public function get(string $provider, string $receiver = null)
    {
        // make the URL for this request
        if (is_null($receiver)) {
            $url = $this->transformUrl(sprintf('%s?ProviderId=%s', self::URI, $provider));
        } else {
            $url = $this->transformUrl(sprintf('%s/%s?ProviderId=%s', self::URI, $receiver, $provider));
        }

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }

    /**
     * Create a receiver.
     *
     * @param  array  $receiver
     * @return object
     */
    public function create(array $receiver)
    {
        // make the URL for this request
        $url = $this->transformUrl(self::URI);

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, $receiver);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $receiver,
        ]);

        // return the response
        return $response;
    }

    /**
     * Create many receivers.
     *
     * @param  array  $receivers
     * @return object
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
     * @return object
     */
    public function delete(string $provider, string $receiver)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/%s/%s', self::URI, $provider, $receiver));

        // get the headers for this request
        $headers = $this->headers->make('DELETE', $url);

        // get the response
        $response = $this->client->delete($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }

    /**
     * Delete many receivers.
     *
     * @param  string $provider
     * @param  array  $receivers
     * @return object
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
     * @return object
     */
    public function deleteAll(string $provider)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/%s', self::URI, $provider));

        // get the headers for this request
        $headers = $this->headers->make('DELETE', $url);

        // get the response
        $response = $this->client->delete($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }
}
