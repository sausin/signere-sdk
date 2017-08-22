<?php

namespace Sausin\Signere;

use Carbon\Carbon;
use GuzzleHttp\Client;
use UnexpectedValueException;

class DocumentFile
{
    use AdjustUrl;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** @var string The environment this is being run in */
    protected $environment;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentFile';

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
     * Returns the signed document as a file.
     *
     * @param  string $documentId
     * @return object
     */
    public function getSigned(string $documentId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/Signed/%s', self::URI, $documentId));

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }

    /**
     * Returns the unsigned document as a file.
     *
     * @param  string $documentId
     * @return object
     */
    public function getUnSigned(string $documentId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/Unsigned/%s', self::URI, $documentId));

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }

    /**
     * Returns the signed document as a PDF file.
     *
     * @param  string $documentId
     * @return object
     */
    public function getSignedPdf(string $documentId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/SignedPDF/%s', self::URI, $documentId));

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
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
     * @return object
     */
    public function temporaryUrl(string $documentId, string $type = 'PDF', Carbon $expiring = null)
    {
        // the file types that can be accepted
        $needTypes = ['SDO', 'PDF', 'SIGNED_PDF', 'MOBILE_SDO', 'XML'];

        // check if specified type is correct
        if (! in_array($type, $needTypes, true)) {
            throw new UnexpectedValueException('File type should be one of '.implode(', ', $needTypes));
        }

        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/TempUrl', self::URI, $documentId));

        // setup body
        $expiring = $expiring ?: Carbon::now()->addHours(48);
        $body = [
            'DocumentId' => $documentId,
            'DocumentFileType' => $type,
            'Expires' => substr($expiring->setTimezone('UTC')->toIso8601String(), 0, 19),
        ];

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, $body);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }
}
