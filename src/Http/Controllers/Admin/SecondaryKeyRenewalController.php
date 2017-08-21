<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Http\Controllers\Controller;

class SecondaryKeyRenewalController extends Controller
{
    /** @var \Sausin\Signere\ApiKey */
    protected $key;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\ApiKey $key
     */
    public function __construct(ApiKey $key)
    {
        parent::__construct();

        $this->key = $key;
    }

    /**
     * Renew the secondary key.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return $this->key->renewSecondary(Config::get('signere.secondary_key'))
                ->getBody()
                ->getContents();
    }
}
