<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\ApiKey;
use Illuminate\Http\Request;
use Sausin\Signere\Http\Controllers\Controller;

class SecondaryKeyRenewalController extends Controller
{
    /**
     * Renew the secondary key.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, ['key' => 'required|string']);
        
        return (new ApiKey)->renewSecondary($request->key)
                ->getBody()
                ->getContents();
    }
}
