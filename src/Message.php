<?php

namespace Sausin\Signere;

class Message extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Message';

    /**
     * Get a list of messages sent for the given document ID.
     *
     * @param  string $messageId
     * @return object
     */
    public function get(string $messageId)
    {
        // make the URL for this request
        $url = sprintf('%s/%s', $this->getBaseUrl(), $messageId);

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
     * Retrieves a list of all the messages that
     * are sent for the given document.
     *
     * @param  string $documentId
     * @return object
     */
    public function all(string $documentId)
    {
        // make the URL for this request
        $url = sprintf('%s/Document/%s', $this->getBaseUrl(), $documentId);

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
     * Sends a message to the signees of a given document.
     *
     * @param  array  $body
     * @return object
     */
    public function sendMessage(array $body)
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
     * Sends a new message to the Signeeref if the first one failed.
     *
     * @param  array  $body
     * @return object
     */
    public function sendNewMessage(array $body)
    {
        // make the URL for this request
        $url = sprintf('%s/SendNewDocumentMessage', $this->getBaseUrl());

        // get the headers for this request
        $headers = $this->headers->make('PUT', $url, $body);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }

    /**
     * Sends a message to an external person with a link/URL
     * to view a document.
     *
     * @param  array  $body
     * @return object
     */
    public function sendExternalMessage(array $body)
    {
        // make the URL for this request
        $url = sprintf('%s/SendExternalMessage', $this->getBaseUrl());

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
