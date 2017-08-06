<?php

namespace Sausin\Signere;

use Carbon\Carbon;

class Headers
{
    /**
     * Make headers for a request.
     *
     * @param  string $reqType
     * @return array
     */
    public static function make(string $reqType)
    {
        // generate timestamp in the correct format
        $timestamp = Carbon::now()->toIso8601String();

        // get the primary key
        $key = Config::get('services.signere.primary_key');

        // set the basic headers
        $headers = [
            'API-ID' => Config::get('services.signere.id'),
            'API-TIMESTAMP' => $timestamp,
            'API-USINGSECONDARYTOKEN' => false,
            'API-ALGORITHM' => 'SHA512',
            'API-RETURNERRORHEADER' => true
        ];

        // make request type specific headers
        if ($reqType === 'GET' || $reqType === 'DELETE') {
            $toEncode = sprintf('%s&Timestamp=%s&Httpverb=%s', $url, $timestamp, $reqType);

            $headers['API-TOKEN'] = hash_hmac('sha512', $toEncode, $key);
        } else {
            $toEncode = sprintf('%s{Timestamp:"%s",Httpverb:"%s"', $data, $timestamp, $reqType);

            $headers['API-TOKEN'] = hash_hmac('sha512', $toEncode, $key);
        }

        return $headers;
    }
}
