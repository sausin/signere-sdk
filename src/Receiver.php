<?php

namespace Sausin\Signere;

class Receiver extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Receiver';

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
            $url = sprintf('%s?ProviderId=%s', $this->getBaseUrl(), $provider);
        } else {
            $url = sprintf('%s/%s?ProviderId=%s', $this->getBaseUrl(), $receiver, $provider);
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
        $url = $this->getBaseUrl();

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
        $url = sprintf('%s/%s/%s', $this->getBaseUrl(), $provider, $receiver);

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
        $url = sprintf('%s/%s', $this->getBaseUrl(), $provider);

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
