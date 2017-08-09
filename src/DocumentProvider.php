<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;
use BadMethodCallException;
use UnexpectedValueException;

class DocumentProvider
{
    /** @var \Guzzle\HttpClient */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentProvider';

    /**
     * Instantiate the class.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client, Headers $headers)
    {
        $this->client = $client;
        $this->headers = $headers;
    }

    /**
     * Retrieves a document provider account.
     *
     * @param  string $providerId
     * @return Object
     */
    public function getProviderAccount(string $providerId)
    {
        // make the URL for this request
        $url = sprintf('%s/%s', self::URI, $providerId);

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Gets the expires date for your BankID certificate. If you don't
     * have your own BankID certificate it will return Bad request.
     *
     * @return Object
     */
    public function getCertExpiry()
    {
        // make the URL for this request
        $url = sprintf('%s/CertificateExpires', self::URI);

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Get the usage when using prepaid or demo account.
     *
     * @param  string       $providerId
     * @param  bool|boolean $demo
     * @return Object
     */
    public function getUsage(string $providerId, bool $demo = false)
    {
        // make the URL for this request
        $url = sprintf(
            '%s/quota/%s?ProviderId=%s',
            self::URI,
            $demo ? 'demo' : 'prepaid',
            $providerId
        );

        // get the headers for this request
        $headers = $this->headers->make('GET', $url);

        // get the response
        $response = $this->client->get($url, [
            'headers' => $headers
        ]);

        // return the response
        return $response;
    }

    /**
     * Creates a new document provider.
     *
     * @param  array  $body
     * @return Object
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
            'Name'
        ];

        // if the body doesn't have needed fields, throw an exception
        if (!array_has_all_keys($body, $needKeys)) {
            throw new BadMethodCallException(
                'Missing fields in input array. Need ' . implode(', ', $needKeys)
            );
        } elseif (isset($body['BillingPlan'])) {
            $expected = ['Small', 'Medium', 'Large'];
            if (!in_array($body['BillingPlan'], $expected)) {
                throw new UnexpectedValueException('BillingPlan should be one of ' . implode(', ', $expected));
            }
        }

        // make the URL for this request
        $url = self::URI;

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, $body);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Updates a new document provider.
     *
     * @param  array  $body
     * @return Object
     */
    public function update(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['Mobile', 'ProviderId'];

        // if the body doesn't have needed fields, throw an exception
        if (!array_has_all_keys($body, $needKeys)) {
            throw new BadMethodCallException(
                'Missing fields in input array. Need ' . implode(', ', $needKeys)
            );
        } elseif (isset($body['BillingPlan'])) {
            $expected = ['Small', 'Medium', 'Large'];
            if (!in_array($body['BillingPlan'], $expected)) {
                throw new UnexpectedValueException('BillingPlan should be one of ' . implode(', ', $expected));
            }
        }

        // make the URL for this request
        $url = self::URI;

        // get the headers for this request
        $headers = $this->headers->make('PUT', $url, $body);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }
}
