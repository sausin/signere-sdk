<?php

namespace Sausin\Signere;

use Carbon\Carbon;
use GuzzleHttp\Client;

class DocumentFile
{
    /** @var \Guzzle\HttpClient */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentFile';

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
     * Returns the signed document as a file.
     * 
     * @param  string $documentId
     * @return Object
     */
    public function getSigned(string $documentId)
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = sprintf('%s/Signed/%s', self::URI, $documentId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Returns the unsigned document as a file.
     * 
     * @param  string $documentId
     * @return Object
     */
    public function getUnSigned(string $documentId)
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = sprintf('%s/Unsigned/%s', self::URI, $documentId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Returns the signed document as a PDF file.
     * 
     * @param  string $documentId
     * @return Object
     */
    public function getSignedPdf(string $documentId)
    {
        // get the headers for this request
        $headers = $this->headers->make('GET');

        // make the URL for this request
        $url = sprintf('%s/SignedPDF/%s', self::URI, $documentId);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a temporary url to a document.
     * 
     * @param  string $documentId
     * @param  string $type
     * @param  Carbon $expiring
     * @return Object
     */
    public function temporaryUrl(string $documentId, string $type, Carbon $expiring)
    {
        // get the headers for this request
        $headers = $this->headers->make('POST');

        // make the URL for this request
        $url = sprintf('%s/TempUrl', self::URI, $documentId);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => [
                'DocumentId' => $documentId,
                'DocumentFileType' => $type,
                'Expires' => $expiring->toIso8601String()
            ]
        ]);

        // return the response
        return $response;
    }
}
