<?php

namespace Sausin\Signere;

use Carbon\Carbon;
use GuzzleHttp\Client;
use UnexpectedValueException;

class Invoice
{
    /** @var \GuzzleHttp\Client */
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
     * @return object
     */
    public function get(int $year, int $month)
    {
        if ($year < 2015 || $year > Carbon::now()->year || $month < 1 || $month > 12) {
            throw new UnexpectedValueException('Invalid year or month');
        }

        // make the URL for this request
        $url = sprintf('%s/%s/%s', self::URI, $year, $month);

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
