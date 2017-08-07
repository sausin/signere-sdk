<?php

namespace Sausin\Signere;

use Carbon\Carbon;

class Headers
{
    /**
     * Make headers for a request.
     * @param  string    $reqType
     * @param  bool|null $needPrimary
     * @return array
     */
    public static function make(string $reqType, string $url, array $body = [], bool $needPrimary = null)
    {
        // generate timestamp in the correct format
        $timestamp = substr(Carbon::now()->setTimezone('UTC')->toIso8601String(), 0, 19);

        // get the primary / secondary key
        $key = $needPrimary ? 
            Config::get('services.signere.primary_key') : 
            Config::get('services.signere.secondary_key');

        // set the basic headers
        $headers = [
            'API-ID' => Config::get('services.signere.id'),
            'API-TIMESTAMP' => $timestamp,
            'API-USINGSECONDARYTOKEN' => is_null($needPrimary) ? true : $needPrimary,
            'API-ALGORITHM' => 'SHA512',
            'API-RETURNERRORHEADER' => true
        ];

        // make request type specific headers
        if ($reqType === 'GET' || $reqType === 'DELETE') {
            $toEncode = sprintf('%s&Timestamp=%s&Httpverb=%s', $url, $timestamp, $reqType);

            $headers['API-TOKEN'] = hash_hmac('sha512', $toEncode, $key);
        } else {
            $toEncode = sprintf('%s{Timestamp:"%s",Httpverb:"%s"}', json_encode($body), $timestamp, $reqType);

            $headers['API-TOKEN'] = hash_hmac('sha512', $toEncode, $key);
        }

        return $headers;
    }
}
