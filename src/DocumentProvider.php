<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class DocumentProvider
{
    /** @var \Guzzle\HttpClient */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentProvider';

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
     * Retrieves a document provider account.
     *
     * @param  string $providerId
     * @return Object
     */
    public function getProvider(string $providerId)
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = sprintf('%s/%s', self::URI, $providerId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Gets the expires date for your BankID certificate. If you don't
     * have your own BankID certificate it will return Bad request.
     *
     * @return Object
     */
    public function getCertExpiry()
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = sprintf('%s/CertificateExpires', self::URI);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Get the usage when using prepaid or demo account.
     *
     * @param  string       $providerId
     * @param  bool|boolean $demo
     * @return Object
     */
    public function getUsage(string $providerId, bool $demo = false)
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = sprintf(
            '%s/quota/%s?ProviderId=%s',
            self::URI,
            $demo ? 'demo' : 'prepaid',
            $providerId
        );

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a new document provider.
     *
     * @param  array  $body
     * @return Object
     */
    public function create(array $body)
    {
        // get the headers for this request
        $headers = $this->headers->make('POST');

        // make the URL for this request
        $url = self::URI;

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Updates a new document provider.
     *
     * @param  array  $body
     * @return Object
     */
    public function update(array $body)
    {
        // get the headers for this request
        $headers = $this->headers->make('PUT');

        // make the URL for this request
        $url = self::URI;

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }
}
