<?php

namespace Sausin\Signere;

trait AdjustUrl
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
        if ($this->environment === 'test' || $this->environment === 'local') {
            return http_build_url($url, $prefix.parse_url($url)['host']);
        }

        return $url;
    }
}
