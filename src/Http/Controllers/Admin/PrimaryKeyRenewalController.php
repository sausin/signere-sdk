<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Http\Controllers\Controller;

class PrimaryKeyRenewalController extends Controller
{
    /** @var \Sausin\Signere\ApiKey */
    protected $key;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\ApiKey $key
     * @return void
     */
    public function __construct(ApiKey $key)
    {
        parent::__construct();

        $this->key = $key;
    }
    
    /**
     * Renew the primary key.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return $this->key->renewPrimary(Config::get('signere.primary_key'))
                ->getBody()
                ->getContents();
    }
}
