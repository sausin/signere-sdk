<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;

class DocumentConvert
{
    /** @var \Guzzle\HttpClient */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentConvert';

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
     * Convert format of the document to PDF.
     *
     * @param  array  $body
     * @return json
     * @todo fix it for proper setup with file
     */
    public function convert(array $body)
    {
        // get the headers for this request
        $headers = $this->headers->make('POST');

        // make the URL for this request
        $url = self::URI;

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }
}
