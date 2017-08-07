<?php

namespace Sausin\Signere;

class DocumentConvert
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentConvert';

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
     * Convert format of the document to PDF.
     *
     * @param  array  $body
     * @return json
     * @todo fix it for proper setup with file
     */
    public static function convert(array $body)
    {
        // get the headers for this request
        $headers = Headers::make('POST');

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
