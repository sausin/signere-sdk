<?php

namespace Sausin\Signere;

use Carbon\Carbon;
use UnexpectedValueException;

class Invoice extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Invoice';

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
