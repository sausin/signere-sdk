<?php

namespace Sausin\Signere\Facades;

use Illuminate\Support\Facades\Facade;

class StatusFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'signere-status';
    }
}
