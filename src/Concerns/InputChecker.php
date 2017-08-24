<?php

namespace Sausin\Signere\Concerns;

use BadMethodCallException;

trait InputChecker
{
    /**
     * Check that the input has needed keys.
     *
     * @param  array  &$input
     * @param  array  &$keys
     * @return \BadMethodCallException|void
     */
    protected function validateHasKeys(array &$input, array &$keys)
    {
        if (! array_has_all_keys($input, $keys)) {
            throw new BadMethodCallException(
                'Missing fields in input array. Need '.implode(', ', $keys)
            );
        }
    }
}
