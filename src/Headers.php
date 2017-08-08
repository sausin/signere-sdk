<?php

namespace Sausin\Signere;

use Carbon\Carbon;
use BadMethodCallException;
use Illuminate\Support\Facades\Config;

class Headers
{
    const VALID_REQUESTS = ['GET', 'POST', 'PUT', 'DELETE'];

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
            throw new BadMethodCallException('Incorrect request type ' . $reqType);
        }

        // check if PUT / POST requests have some data assigned to body
        if (($reqType === 'PUT' || $reqType === 'POST') && empty($body)) {
            throw new BadMethodCallException('Empty body not allowed with ' . $reqType . ' request');
        }

        // generate timestamp in the correct format
        $timestamp = substr(Carbon::now()->setTimezone('UTC')->toIso8601String(), 0, 19);

        // get the primary / secondary key
        $key = $needPrimary ?
            Config::get('signere.primary_key') :
            Config::get('signere.secondary_key');

        // set the basic headers
        $headers = [
            'API-ID' => Config::get('signere.id'),
            'API-TIMESTAMP' => $timestamp,
            'API-USINGSECONDARYTOKEN' => is_null($needPrimary) ? true : $needPrimary,
            'API-ALGORITHM' => 'SHA512',
            'API-RETURNERRORHEADER' => true
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
