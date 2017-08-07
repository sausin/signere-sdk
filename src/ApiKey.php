<?php

namespace Sausin\Signere;

class ApiKey
{
    /** @var $client Guzzle Http Client */
    protected $client;

    /** The URI of the action */
    const URI = 'https://api.signere.no/api/ApiToken';

    /**
     * Instantiate the class.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Renews the primary API key.
     *
     * @param  string $key
     * @return json
     */
    public static function renewPrimary(string $key)
    {
        // get the headers for this request
        $headers = Headers::make('POST', true);

        // make the URL for this request
        $url = $this->makeUrl('POST', 1, $key);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Renews the secondary API key.
     *
     * @param  string $key
     * @return json
     */
    public static function renewSecondary(string $key)
    {
        // get the headers for this request
        $headers = Headers::make('POST', true);

        // make the URL for this request
        $url = $this->makeUrl('POST', 2, $key);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Generate a new primary key and return it.
     *
     * @param  string $providerId
     * @param  int    $otpCode
     * @return json
     */
    public static function createPrimary(string $providerId, int $otpCode)
    {
        // get the headers for this request
        $headers = Headers::make('POST', false);

        // make the URL for this request
        $url = $this->makeUrl('POST', null, null, $providerId, $otpCode);

        // get the response
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the response
        return $response;
    }

    /**
     * Sends an OTP code in an SMS to
     * the given mobile number.
     *
     * @param  array  $body
     * @return json
     */
    public static function recoverPrimary(array $body)
    {
        // get the headers for this request
        $headers = Headers::make('PUT', false);

        // make the URL for this request
        $url = $this->makeUrl('PUT');

        // get the response
        $response = $this->client->put($url, [
            'headers' => $headers,
            'json' => $body
        ]);

        // return the status
        return $response->getStatusCode();
    }

    /**
     * Generate the url for different types of requests.
     *
     * @param  int|null    $level
     * @param  string|null $key
     * @param  string|null $providerId
     * @param  int|null    $otp
     * @return string
     */
    private function makeUrl(int $level = null, string $key = null, string $providerId = null, int $otp = null)
    {
        // POST Requests
        if ($reqType === 'POST') {
            if ($level === 1) {
                return sprintf('%s/RenewPrimaryKey?OldPrimaryKey=%s', self::URI, $key);
            } elseif ($level === 2) {
                return sprintf('%s/RenewSecondaryKey?OldSecondaryKey=%s', self::URI, $key);
            }

            return sprintf(
                '%s/OTP/RenewPrimaryKeyStep2/Provider/%s/OTPCode/%s',
                self::URI,
                $providerId,
                $otp
            );
        }

        // PUT Requests
        return sprintf('%s/OTP/RenewPrimaryKeyStep1', self::URI);
    }
}
