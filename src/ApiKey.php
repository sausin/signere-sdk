<?php

namespace Sausin\Signere;

use GuzzleHttp\Client;
use BadMethodCallException;
use InvalidArgumentException;

class ApiKey
{
    use AdjustUrl;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var Headers */
    protected $headers;

    /** @var string The environment this is being run in */
    protected $environment;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/ApiToken';

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
     * Renews the primary API key.
     *
     * @param  string $key
     * @return object
     */
    public function renewPrimary(string $key)
    {
        // make the URL for this request
        $url = sprintf('%s/RenewPrimaryKey?OldPrimaryKey=%s', self::URI, $key);

        // get the headers for this request
        $headers = $this->headers->make('POST', $this->transformUrl($url), [], true);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => [],
        ]);

        // return the response
        return $response;
    }

    /**
     * Renews the secondary API key.
     *
     * @param  string $key
     * @return object
     */
    public function renewSecondary(string $key)
    {
        // make the URL for this request
        $url = sprintf('%s/RenewSecondaryKey?OldSecondaryKey=%s', self::URI, $key);

        // get the headers for this request
        $headers = $this->headers->make('POST', $this->transformUrl($url), [], true);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => [],
        ]);

        // return the response
        return $response;
    }

    /**
     * Generate a new primary key and return it.
     *
     * @param  string $providerId
     * @param  int    $otpCode
     * @return object
     */
    public function createPrimary(string $providerId, int $otpCode)
    {
        // make the URL for this request
        $url = sprintf(
            '%s/OTP/RenewPrimaryKeyStep2/Provider/%s/OTPCode/%s',
            self::URI,
            $providerId,
            $otpCode
        );

        // get the headers for this request
        $headers = $this->headers->make('POST', $this->transformUrl($url), [], null);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => [],
        ]);

        // return the response
        return $response;
    }

    /**
     * Sends an OTP code in an SMS to
     * the given mobile number.
     *
     * @param  array  $body
     * @return object
     */
    public function recoverPrimary(array $body)
    {
        // keys that are mandatory for this request
        $needKeys = ['MobileNumber', 'ProviderID'];

        // if the body doesn't have needed fields, throw an exception
        if (! array_has_all_keys($body, $needKeys)) {
            throw new BadMethodCallException(
                'Missing fields in input array. Need '.implode(', ', $needKeys)
            );
        } elseif (isset($body['SmsMessage'])) {
            if (! preg_match('/\{0\}/', $body['SmsMessage'])) {
                throw new InvalidArgumentException('SmsMessage must contain a {0}');
            }
        }

        // make the URL for this request
        $url = sprintf('%s/OTP/RenewPrimaryKeyStep1', self::URI);

        // get the headers for this request
        $headers = $this->headers->make('PUT', $this->transformUrl($url), $body, false);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }
}
