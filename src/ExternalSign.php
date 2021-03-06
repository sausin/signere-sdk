<?php

namespace Sausin\Signere;

use BadMethodCallException;
use UnexpectedValueException;

class ExternalSign extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/externalsign';

    /**
     * Return the login information from the login.
     *
     * @param  string $documentId
     * @return object
     */
    public function getUrlForSign(string $documentId)
    {
        // make the URL for this request
        $url = sprintf('%s/%s', $this->getBaseUrl(), $documentId);

        // get the headers for this request
        $headers = $this->headers->make('GET', $url, [], true);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }

    /**
     * Get the URLs to a viewerapplet showing
     * documents in an iframe on website.
     *
     * @param  string $documentId
     * @param  array  $params
     * @return object
     */
    public function getUrlForApplet(string $documentId, array $params)
    {
        if (! isset($params['Domain']) || ! isset($params['Language'])) {
            throw new BadMethodCallException('Params should contain "Domain" and "Language" keys');
        }

        // make the URL for this request
        $url = sprintf(
            '%s/ViewerUrl/%s/%s/%s',
            $this->getBaseUrl(),
            $documentId,
            $params['Domain'],
            $params['Language']
        );

        // get the headers for this request
        $headers = $this->headers->make('GET', $url, [], true);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }

    /**
     * Get status of BankId mobile sign session.
     *
     * @param  string $signeeRefId
     * @return object
     */
    public function getSessionStatus(string $signeeRefId)
    {
        // make the URL for this request
        $url = sprintf('%s/BankIDMobileSign/Status/%s', $this->getBaseUrl(), $signeeRefId);

        // get the headers for this request
        $headers = $this->headers->make('GET', $url, [], true);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a externalsign request to integrate
     * signing of documents in a website.
     *
     * @param  array  $body
     * @return object
     */
    public function createRequest(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = [
            'Description',
            'ExternalDocumentId',
            'FileContent',
            'Filename',
            'ReturnUrlError',
            'ReturnUrlSuccess',
            'ReturnUrlUserAbort',
            'SigneeRefs',
            'Title',
        ];

        // keys that need to be present in each signeeref
        $needSubKeys = [
            'UniqueRef',
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
     * Creates a app launch uri for the BankID app.
     *
     * @param  array  $body
     * @return object
     */
    public function createAppUrl(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['DocumentId', 'SigneeRefId', 'UserAgent'];

        // if the body doesn't have needed fields, throw an exception
        $this->validateHasKeys($body, $needKeys);

        // make the URL for this request
        $url = sprintf('%s/BankIDAppUrl', $this->getBaseUrl());

        // get the headers for this request
        $headers = $this->headers->make('PUT', $url, $body, true);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }

    /**
     * Starts a BankID mobile sign session for the given document
     * with given mobilenumber and date of birth.
     *
     * @param  array  $body
     * @return object
     */
    public function startMobile(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['DateOfBirth', 'DocumentId', 'Mobile', 'SigneeRefId'];

        // if the body doesn't have needed fields, throw an exception
        $this->validateHasKeys($body, $needKeys);

        // make the URL for this request
        $url = sprintf('%s/BankIDMobileSign', $this->getBaseUrl());

        // get the headers for this request
        $headers = $this->headers->make('PUT', $url, $body, true);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }
}
