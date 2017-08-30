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
        $url = $this->getBaseUrl();

        // get the response
        $response = $this->client->post($url, [
            'json' => $body,
        ]);

        // return the response
        return $response;
    }
}
