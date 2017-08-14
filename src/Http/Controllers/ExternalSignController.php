<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Sausin\Signere\ExternalSign;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Http\Controllers\Controller;

class ExternalSignController extends Controller
{
    /** @var \Sausin\Signere\ExternalSign */
    protected $extSign;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\ExternalSign $extSign
     * @return void
     */
    public function __construct(ExternalSign $extSign)
    {
        parent::__construct();

        $this->extSign = $extSign;
    }

    
}
