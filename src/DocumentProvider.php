<?php

namespace Sausin\Signere;

class DocumentProvider
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentProvider';

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
     * Retrieves a document provider account.
     *
     * @param  string $providerId
     * @return Object
     */
    public static function getProvider(string $providerId)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

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
    public static function getCertExpiry()
    {
        // get the headers for this request
        $headers = Headers::make('GET');

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
    public static function getUsage(string $providerId, bool $demo = false)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

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
    public static function create(array $body)
    {
        // get the headers for this request
        $headers = Headers::make('POST');

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
    public static function update(array $body)
    {
        // get the headers for this request
        $headers = Headers::make('PUT');

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
