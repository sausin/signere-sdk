<?php

namespace Sausin\Signere;

use Carbon\Carbon;
use GuzzleHttp\Client;
use UnexpectedValueException;

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
        // make the URL for this request
        $url = sprintf('%s/Signed/%s', self::URI, $documentId);

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

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
        // make the URL for this request
        $url = sprintf('%s/Unsigned/%s', self::URI, $documentId);

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

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
        // make the URL for this request
        $url = sprintf('%s/SignedPDF/%s', self::URI, $documentId);

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

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
     * @param  string      $documentId
     * @param  string      $type
     * @param  Carbon|null $expiring
     * @return Object
     */
    public function temporaryUrl(string $documentId, string $type = 'PDF', Carbon $expiring = null)
    {
        // the file types that can be accepted
        $needTypes = ['SDO', 'PDF', 'SIGNED_PDF', 'MOBILE_SDO', 'XML'];

        // check if specified type is correct
        if (!in_array($type, $needTypes, true)) {
            throw new UnexpectedValueException('File type should be one of ' . implode(', ', $needTypes));
        }

        // make the URL for this request
        $url = sprintf('%s/TempUrl', self::URI, $documentId);

        // setup body
        $expiring = $expiring ?: Carbon::now()->addHours(48);
        $body = [
            'DocumentId' => $documentId,
            'DocumentFileType' => $type,
            'Expires' => substr($expiring->setTimezone('UTC')->toIso8601String(), 0, 19)
        ];

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, $body);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }
}
