<?php

if (! function_exists('array_has_all_keys')) {
    /**
     * Check if a given array has the given keys.
     *
     * @param  array  $array
     * @param  array  $keys
     * @return array
     */
    function array_has_all_keys(array $array, array $keys)
    {
        return count(array_intersect(array_keys($array), $keys)) === count($keys);
    }
}
