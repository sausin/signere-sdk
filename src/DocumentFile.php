<?php

namespace Sausin\Signere;

use Carbon\Carbon;
use UnexpectedValueException;

class DocumentFile extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentFile';

    /**
     * Returns the signed document as a file.
     *
     * @param  string $documentId
     * @return object
     */
    public function getSigned(string $documentId)
    {
        // make the URL for this request
        $url = sprintf('%s/Signed/%s', $this->getBaseUrl(), $documentId);

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
        $url = sprintf('%s/Unsigned/%s', $this->getBaseUrl(), $documentId);

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
        $url = sprintf('%s/SignedPDF/%s', $this->getBaseUrl(), $documentId);

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
        $url = sprintf('%s/TempUrl', $this->getBaseUrl(), $documentId);

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
