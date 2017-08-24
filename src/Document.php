<?php

namespace Sausin\Signere;

class Document extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Document';

    /**
     * Returns the url to sign the document for the given Signeeref
     * or the first Signeeref if not SigneerefId is specified.
     *
     * @param  string      $documentId
     * @param  string|null $signeeRefId
     * @return object
     */
    public function getSignUrl(string $documentId, string $signeeRefId = null)
    {
        // make the URL for this request
        $url = sprintf(
            '%s/SignUrl?documentId=%s&signeeRefId=%s',
            $this->getBaseUrl(),
            $documentId,
            $signeeRefId
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

    /**
     * Retrieves the document with the given ID.
     *
     * @param  string      $documentId
     * @return object
     */
    public function get(string $documentId)
    {
        // make the URL for this request
        $url = sprintf('%s/%s', $this->getBaseUrl(), $documentId);

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
     * Returns a temporary URL for viewing a signed
     * document in the BankID applet.
     *
     * @param  string      $documentId
     * @return object
     */
    public function getTemporaryUrl(string $documentId)
    {
        // make the URL for this request
        $url = sprintf(
            '%s/SignedDocument/TemporaryViewerUrl/%s',
            $this->getBaseUrl(),
            $documentId
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

    /**
     * Retrieves a list of documents based on the given parameters.
     *
     * @param  string|null $jobId
     * @param  array       $params
     * @return object
     */
    public function getList(string $jobId = null, array $params = [])
    {
        // make the URL for this request
        $url = sprintf(
            '%s/?Status=%s&Fromdate=%s&JobId=%s&CreatedAfter=%s&ExternalCustomerRef=%s',
            $this->getBaseUrl(),
            isset($params['status']) ? $params['status'] : 'All',
            isset($params['from_date']) ? $params['from_date'] : null,
            $jobId,
            isset($params['created_after']) ? $params['created_after'] : null,
            isset($params['ext_cust_ref']) ? $params['ext_cust_ref'] : null
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

    /**
     * Creates a new document to sign, and returns
     * a document response object.
     *
     * @param  array  $body
     * @return object
     */
    public function create(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = [
            'Description',
            'FileContent',
            'FileMD5CheckSum',
            'FileName',
            'Language',
            'SigneeRefs',
            'SignJobId',
            'Title',
        ];

        // keys that need to be present in each signeeref
        $needSubKeys = [
            'SigneeRefId',
            'FirstName',
            'LastName',
            'Email',
        ];

        // if the body doesn't have needed fields, throw an exception
        $this->validateHasKeys($body, $needKeys);

        if (! is_array($body['SigneeRefs'])) {
            throw new UnexpectedValueException('SigneeRefs key in input should be an array');
        }

        foreach ($body['SigneeRefs'] as $ref) {
            if (! is_array($ref)) {
                throw new UnexpectedValueException('Each item in SigneeRefs should be an array');
            }

            // the SignreeRefs should have some fields
            $this->validateHasKeys($ref, $needSubKeys);
        }

        // make the URL for this request
        $url = $this->getBaseUrl();

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, $body, true);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a new document to sign, and returns
     * a document response object.
     *
     * @param  array  $body
     * @return object
     */
    public function cancel(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['CanceledDate', 'DocumentID', 'Signature'];

        // if the body doesn't have needed fields, throw an exception
        $this->validateHasKeys($body, $needKeys);

        // make the URL for this request
        $url = sprintf('%s/CancelDocument', $this->getBaseUrl());

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

    /**
     * Creates a new document to sign, and returns
     * a document response object.
     *
     * @param  array  $body
     * @return object
     */
    public function changeDeadline(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['DocumentID', 'NewDeadline', 'ProviderID'];

        // if the body doesn't have needed fields, throw an exception
        $this->validateHasKeys($body, $needKeys);

        // make the URL for this request
        $url = sprintf('%s/ChangeDeadline', $this->getBaseUrl());

        // get the headers for this request
        $headers = $this->headers->make('PUT', $url, $body);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }
}
