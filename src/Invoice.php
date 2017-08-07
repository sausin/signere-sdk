<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class Invoice
{
    /** @var \Guzzle\HttpClient */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Invoice';

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
     * Returns a list of invoice transactions for
     * the given year / month combination.
     *
     * @param  int    $year
     * @param  int    $month
     * @return json
     */
    public function get(int $year, int $month)
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = sprintf('%s/%s/%s', self::URI, $year, $month);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }
}
