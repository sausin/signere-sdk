<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

abstract class BaseClass
{
    use Concerns\UrlTransformer,
        Concerns\InputChecker;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** @var string The environment this is being run in */
    protected $environment;

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
     * Get the base URL. This handles the correct
     * url generation based on production and
     * test environment.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->transformUrl(static::URI);
    }
}
