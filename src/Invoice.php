<?php

namespace Sausin\Signere;

use Carbon\Carbon;
use GuzzleHttp\Client;
use UnexpectedValueException;

class Invoice
{
    use AdjustUrl;
    
    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** @var string The environment this is being run in */
    protected $environment;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Invoice';

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
        $url = $this->transformUrl(sprintf('%s/%s/%s', self::URI, $year, $month));

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
