<?php

namespace Sausin\Signere\Facades;

use Illuminate\Support\Facades\Facade;

class SignereDocumentConvert extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'signere-document-convert';
    }
}
