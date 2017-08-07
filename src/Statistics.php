<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class Statistics
{
    /** @var \Guzzle\HttpClient */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Statistics';

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
     * Returns a statistics response object with various information.
     *
     * @param  int|null $year
     * @param  int|null $month
     * @param  int|null $day
     * @param  string   $status
     * @return Object
     */
    public function get(int $year = null, int $month = null, int $day = null, string $status = 'All')
    {
        // make the URL for this request
        $url = sprintf(
            '%s?Year=%s&Month=%s&Day=%s&Status=%s',
            self::URI,
            $year,
            $month,
            $day,
            $status
        );

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }
}
