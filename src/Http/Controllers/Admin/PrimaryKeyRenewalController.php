<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\ApiKey;
use Illuminate\Http\Request;
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
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, ['key' => 'required|string']);
        
        return $this->key->renewPrimary($request->key)
                ->getBody()
                ->getContents();
    }
}
