<?php

namespace Sausin\Signere;

class Statistics
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Statistics';

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
     * Returns a statistics response object with various information.
     *
     * @param  int|null $year
     * @param  int|null $month
     * @param  int|null $day
     * @param  string   $status
     * @return Object
     */
    public static function get(int $year = null, int $month = null, int $day = null, string $status = 'All')
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = sprintf(
            '%s?Year=%s&Month=%s&Day=%s&Status=%s',
            self::URI,
            $year,
            $month,
            $day,
            $status
        );

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }
}
