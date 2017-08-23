<?php

namespace Sausin\Signere;

class Statistics extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Statistics';

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
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }
}
