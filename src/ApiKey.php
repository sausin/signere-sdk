<?php

namespace Sausin\Signere;

use BadMethodCallException;
use InvalidArgumentException;

class ApiKey extends BaseClass
{
    /** The URI of the action */
    const URI = 'https://api.signere.no/api/ApiToken';

    /**
     * Renews the primary API key.
     *
     * @param  string $key
     * @return object
     */
    public function renewPrimary(string $key)
    {
        // make the URL for this request
        $url = $this->transformUrl(sprintf('%s/RenewPrimaryKey?OldPrimaryKey=%s', self::URI, $key));

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, [], true);

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
        $url = $this->transformUrl(sprintf('%s/RenewSecondaryKey?OldSecondaryKey=%s', self::URI, $key));

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, [], true);

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
        $url = $this->transformUrl(sprintf(
            '%s/OTP/RenewPrimaryKeyStep2/Provider/%s/OTPCode/%s',
            self::URI,
            $providerId,
            $otpCode
        ));

        // get the headers for this request
        $headers = $this->headers->make('POST', $url, [], null);

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
        $url = $this->transformUrl(sprintf('%s/OTP/RenewPrimaryKeyStep1', self::URI));

        // get the headers for this request
        $headers = $this->headers->make('PUT', $url, $body, false);

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        // return the response
        return $response;
    }
}
