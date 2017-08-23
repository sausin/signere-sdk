<?php

namespace Sausin\Signere;

class DocumentConvert extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentConvert';

    /**
     * Convert format of the document to PDF.
     *
     * @param  array  $body
     * @return object
     * @todo fix it for proper setup with file
     */
    public function convert(array $body)
    {
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
