<?php

namespace Sausin\Signere;

class RequestId extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/SignereId';

    /**
     * Retrives a SignereID session to get the
     * information about the authorized user.
     *
     * @param  string $requestId
     * @param  bool   $metadata
     * @return object
     */
    public function getDetails(string $requestId, bool $metadata)
    {
        // make the URL for this request
        $url = sprintf(
            '%s/%s?metadata=%s',
            $this->getBaseUrl(),
            $requestId,
            $metadata ? 'true' : 'false'
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
     * Check if a SignereID session is completed or not.
     *
     * @param  string $requestId
     * @return object
     */
    public function check(string $requestId)
    {
        // make the URL for this request
        $url = sprintf('%s/Completed/%s', $this->getBaseUrl(), $requestId);

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
     * Creates a SignereID request, and returns a url.
     *
     * @param  array  $body
     * @return string
     */
    public function create(array $body)
    {
        // make the URL for this request
        $url = $this->getBaseUrl();

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
     * Invalidates a SignereID request.
     *
     * @param  string $requestId
     * @return object
     */
    public function invalidate(string $requestId)
    {
        // make the URL for this request
        $url = sprintf('%s/Invalidate', $this->getBaseUrl());

        $body = ['RequestId' => $requestId];

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
