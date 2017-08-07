<?php

namespace Sausin\Signere;

class DocumentJob
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentJob';

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
     * Retrieves a document job in the form of a response
     * object containing the document job parameters.
     *
     * @param  string $jobId
     * @return Object
     */
    public static function get(string $jobId)
    {
        // get the headers for this request
        $headers = Headers::make('GET');

        // make the URL for this request
        $url = sprintf('%s/%s', self::URI, $jobId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a document job.
     *
     * @param  array  $body
     * @return Object
     */
    public static function create(array $body)
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
