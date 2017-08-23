<?php

namespace Sausin\Signere;

trait UrlTransformer
{
    /**
     * Adjust the URL to production or test environment.
     *
     * @param  string $url
     * @param  string $prefix
     * @return string
     */
    protected function transformUrl(string $url, string $prefix = 'test')
    {
        $check = $this->environment;

        if ($check === 'test' || $check === 'local' || $check === 'testing') {
            return http_build_url($url, ['host' => $prefix.parse_url($url)['host']]);
        }

        return $url;
    }
}
