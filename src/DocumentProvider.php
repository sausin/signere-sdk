<?php

namespace Sausin\Signere;

use UnexpectedValueException;

class DocumentProvider extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentProvider';

    /**
     * Retrieves a document provider account.
     *
     * @param  string $providerId
     * @return object
     */
    public function getProviderAccount(string $providerId)
    {
        // make the URL for this request
        $url = sprintf('%s/%s', $this->getBaseUrl(), $providerId);

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
     * Gets the expires date for your BankID certificate. If you don't
     * have your own BankID certificate it will return Bad request.
     *
     * @return object
     */
    public function getCertExpiry()
    {
        // make the URL for this request
        $url = sprintf('%s/CertificateExpires', $this->getBaseUrl());

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
     * Get the usage when using prepaid or demo account.
     *
     * @param  string       $providerId
     * @param  bool|bool $demo
     * @return object
     */
    public function getUsage(string $providerId, bool $demo = false)
    {
        // make the URL for this request
        $url = sprintf(
            '%s/quota/%s?ProviderId=%s',
            $this->getBaseUrl(),
            $demo ? 'demo' : 'prepaid',
            $providerId
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
     * Creates a new document provider.
     *
     * @param  array  $body
     * @return object
     */
    public function create(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = [
            'BillingAddress1',
            'BillingCity',
            'BillingPostalCode',
            'CompanyEmail',
            'CompanyPhone',
            'DealerId',
            'LegalContactEmail',
            'LegalContactName',
            'LegalContactPhone',
            'MvaNumber',
            'Name',
        ];

        // if the body doesn't have needed fields, throw an exception
        $this->validateHasKeys($body, $needKeys);

        if (isset($body['BillingPlan'])) {
            $expected = ['Small', 'Medium', 'Large'];
            if (! in_array($body['BillingPlan'], $expected)) {
                throw new UnexpectedValueException('BillingPlan should be one of '.implode(', ', $expected));
            }
        }

        // make the URL for this request
        $url = $this->getBaseUrl();

        // get the response
        $response = $this->client->post($url, [
            'json' => $body,
        ]);

        // return the response
        return $response;
    }

    /**
     * Updates a new document provider.
     *
     * @param  array  $body
     * @return object
     */
    public function update(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['Mobile', 'ProviderId'];

        // if the body doesn't have needed fields, throw an exception
        $this->validateHasKeys($body, $needKeys);

        if (isset($body['BillingPlan'])) {
            $expected = ['Small', 'Medium', 'Large'];
            if (! in_array($body['BillingPlan'], $expected)) {
                throw new UnexpectedValueException('BillingPlan should be one of '.implode(', ', $expected));
            }
        }

        // make the URL for this request
        $url = $this->getBaseUrl();

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
