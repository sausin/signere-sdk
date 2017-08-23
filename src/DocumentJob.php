<?php

namespace Sausin\Signere;

use BadMethodCallException;

class DocumentJob extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentJob';

    /**
     * Retrieves a document job in the form of a response
     * object containing the document job parameters.
     *
     * @param  string $jobId
     * @return object
     */
    public function get(string $jobId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/%s', self::URI, $jobId));

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
     * Creates a document job.
     *
     * @param  array  $body
     * @return object
     */
    public function create(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['Contact_Email', 'Contact_Phone'];

        // if the body doesn't have needed fields, throw an exception
        if (! array_has_all_keys($body, $needKeys)) {
            throw new BadMethodCallException(
                'Missing fields in input array. Need '.implode(', ', $needKeys)
            );
        }

        // make the URL for this request
        $url = $this->transformUrl(self::URI);

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
