<?php

namespace Sausin\Signere;

class Events extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/events/encryptionkey';

    /**
     * Returns the EventsQueue encryptionKey as a base64 encoded string.
     *
     * @return object
     */
    public function getEncryptionKey()
    {
        // make the URL for this request
        $url = $this->getBaseUrl();

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        // return the response
        return $response;
    }
}
