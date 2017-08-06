<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class ExternalLogin
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/ExternalLogin';

    /**
     * Instantiate the class.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public static function get()
    {

    }

    public static function create()
    {

    }

    public static function createMobile()
    {
        
    }

    public static function startMobile()
    {

    }

    public static function delete()
    {

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
