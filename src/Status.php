<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;
use Illuminate\Contracts\Config\Repository as Config;

class Status extends BaseClass
{
    /** @var \Illuminate\Contracts\Config\Repository */
    protected $config;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Status';

    /**
     * Instantiate the class.
     *
     * @param Client  $client
     * @param Headers $headers
     * @param Config  $config
     * @param string  $environment
     */
    public function __construct(Client $client, Headers $headers, Config $config, $environment = null)
    {
        $this->client = $client;
        $this->headers = $headers;
        $this->config = $config;
        $this->environment = $environment;
    }

    /**
     * Returns the UTC time of the server.
     *
     * @return object
     */
    public function getServerTime()
    {
        // make the URL for this request
        $url = sprintf('%s/ServerTime', $this->getBaseUrl());

        // get the response
        $response = $this->client->get($url);

        // return the response
        return $response;
    }

    /**
     * Returns the status the server.
     *
     * @param  string $request
     * @return object
     */
    public function getServerStatus(string $request = 'test')
    {
        // make the URL for this request
        $url = sprintf('%s/Ping/%s', $this->getBaseUrl(), $request);

        // get the response
        $response = $this->client->get($url, [
            'headers' => array_merge(
                ['PingToken' => $this->config->get('signere.ping_token')]
            ),
        ]);

        // return the response
        return $response;
    }
}
