<?php

namespace Sausin\Signere;

use Carbon\Carbon;

class Form extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/Form';

    /**
     * Gets all forms to the authenticated documentprovider.
     *
     * @return object
     */
    public function get()
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/GetForms', self::URI));

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
     * Gets all signed forms to the authenticated documentprovider
     * filtered by the input parameters provided.
     *
     * @param  string|null $formId
     * @param  Carbon|null $from
     * @param  Carbon|null $to
     * @return object
     */
    public function getAllSigned(string $formId = null, Carbon $from = null, Carbon $to = null)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf(
            '%s/GetSignedForms?formId=%s&fromDate=%s&toDate=%s',
            self::URI,
            $formId,
            $from ? $from->toDateString() : null,
            $to ? $to->toDateString() : null
        ));

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
     * Gets the attachements to the form.
     *
     * @param  string $formId
     * @param  string $formSignId
     * @param  string $attachReference
     * @return object
     */
    public function getAttachments(string $formId, string $formSignId, string $attachReference)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf(
            '%s/GetFormAttachment?formId=%s&FormSignatureId=%s&AttatchmentReference=%s',
            self::URI,
            $formId,
            $formSignId,
            $attachReference
        ));

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
     * Gets a signed form from DocumentID.
     *
     * @param  string $documentId
     * @return object
     */
    public function getSignedByDocId(string $documentId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/GetSignedForm?documentid=%s', self::URI, $documentId));

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
     * Gets a signed form from formId and formSessionId.
     *
     * @param  string $formId
     * @param  string $formSessionId
     * @return object
     */
    public function getSignedBySessionId(string $formId, string $formSessionId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf(
            '%s/GetSignedFormByFormSessionId?formId=%s&formSessionId=%s',
            self::URI,
            $formId,
            $formSessionId
        ));

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
     * Enables the form.
     *
     * @param  string $formId
     * @return object
     */
    public function enable(string $formId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/EnableForm?formId=%s', self::URI, $formId));

        // get the headers for this request
        $headers = $this->headers->make('PUT', $url, []);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => [],
        ]);

        // return the response
        return $response;
    }

    /**
     * Disables the form.
     *
     * @param  string $formId
     * @return object
     */
    public function disable(string $formId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/DisableForm?formId=%s', self::URI, $formId));

        // get the headers for this request
        $headers = $this->headers->make('PUT', $url, []);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => [],
        ]);

        // return the response
        return $response;
    }
}
