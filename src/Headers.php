<?php

namespace Sausin\Signere;

use Carbon\Carbon;
use UnexpectedValueException;
use Illuminate\Contracts\Config\Repository as Config;

class Headers
{
    /** @var \Illuminate\Contracts\Config\Repository */
    protected $config;

    /** valid request types */
    const VALID_REQUESTS = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * Instantiate the class.
     *
     * @param \Illuminate\Contracts\Config\Repository
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Make headers for a request.
     *
     * @param  string    $reqType
     * @param  bool|null $needPrimary
     * @return array
     */
    public function make(string $reqType, string $url, array $body = [], bool $needPrimary = null)
    {
        // check if the request type is valid
        if (! in_array($reqType, self::VALID_REQUESTS)) {
            throw new UnexpectedValueException('Incorrect request type '.$reqType);
        }

        // generate timestamp in the correct format
        $timestamp = substr(Carbon::now()->setTimezone('UTC')->toIso8601String(), 0, 19);

        if (is_bool($needPrimary) && ! $needPrimary) {
            // this is for the case when new key
            // is needed to be generated
            $key = '';
        } else {
            // get the primary / secondary key
            $key = $needPrimary ?
                $this->config->get('signere.primary_key') :
                $this->config->get('signere.secondary_key');
        }

        // set the basic headers
        $headers = [
            'API-ID' => $this->config->get('signere.id'),
            'API-TIMESTAMP' => $timestamp,
            'API-USINGSECONDARYTOKEN' => is_null($needPrimary) ? true : $needPrimary,
            'API-ALGORITHM' => 'SHA512',
            'API-RETURNERRORHEADER' => true,
        ];

        // make request type specific headers
        if ($reqType === 'GET' || $reqType === 'DELETE') {
            $toEncode = sprintf('%s&Timestamp=%s&Httpverb=%s', $url, $timestamp, $reqType);

            $headers['API-TOKEN'] = hash_hmac('sha512', $toEncode, $key);
        } elseif ($reqType === 'PUT' || $reqType === 'POST') {
            $toEncode = sprintf('%s{Timestamp:"%s",Httpverb:"%s"}', json_encode($body), $timestamp, $reqType);

            $headers['API-TOKEN'] = hash_hmac('sha512', $toEncode, $key);
        }

        return $headers;
    }
}
