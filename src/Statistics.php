<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class Statistics
{
    use AdjustUrl;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** @var string The environment this is being run in */
    protected $environment;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Statistics';

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
     * Returns a statistics response object with various information.
     *
     * @param  int|null $year
     * @param  int|null $month
     * @param  int|null $day
     * @param  string   $status
     * @return object
     */
    public function get(int $year = null, int $month = null, int $day = null, string $status = 'All')
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf(
            '%s?Year=%s&Month=%s&Day=%s&Status=%s',
            self::URI,
            $year,
            $month,
            $day,
            $status
        ));

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }
}
