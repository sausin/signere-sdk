<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;
use BadMethodCallException;
use UnexpectedValueException;

class DocumentProvider
{
    use AdjustUrl;
    
    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** @var string The environment this is being run in */
    protected $environment;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/DocumentProvider';

    /**
     * Instantiate the class.
     *
     * @param Client  $client
     * @param Headers $headers
     * @param string  $environment
     */
    public function __construct(Client $client, Headers $headers, $environment = null)
    {
        $this->client = $client;
        $this->headers = $headers;
        $this->environment = $environment;
    }

    /**
     * Retrieves a document provider account.
     *
     * @param  string $providerId
     * @return object
     */
    public function getProviderAccount(string $providerId)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/%s', self::URI, $providerId));

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
        $url = $this->transformUrl(sprintf('%s/CertificateExpires', self::URI));

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
        $url = $this->transformUrl(sprintf(
            '%s/quota/%s?ProviderId=%s',
            self::URI,
            $demo ? 'demo' : 'prepaid',
            $providerId
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
        if (! array_has_all_keys($body, $needKeys)) {
            throw new BadMethodCallException(
                'Missing fields in input array. Need '.implode(', ', $needKeys)
            );
        } elseif (isset($body['BillingPlan'])) {
            $expected = ['Small', 'Medium', 'Large'];
            if (! in_array($body['BillingPlan'], $expected)) {
                throw new UnexpectedValueException('BillingPlan should be one of '.implode(', ', $expected));
            }
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
        if (! array_has_all_keys($body, $needKeys)) {
            throw new BadMethodCallException(
                'Missing fields in input array. Need '.implode(', ', $needKeys)
            );
        } elseif (isset($body['BillingPlan'])) {
            $expected = ['Small', 'Medium', 'Large'];
            if (! in_array($body['BillingPlan'], $expected)) {
                throw new UnexpectedValueException('BillingPlan should be one of '.implode(', ', $expected));
            }
        }

        // make the URL for this request
        $url = $this->transformUrl(self::URI);

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
