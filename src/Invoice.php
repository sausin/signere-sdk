<?php

namespace Sausin\Signere;

class Invoice
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Invoice';

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
     * Returns a list of invoice transactions for
     * the given year / month combination.
     *
     * @param  int    $year
     * @param  int    $month
     * @return json
     */
    public static function get(int $year, int $month)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

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
