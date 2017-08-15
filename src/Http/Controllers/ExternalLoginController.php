<?php

namespace Sausin\Signere\Http\Controllers;

use Illuminate\Http\Request;
use Sausin\Signere\ExternalLogin;
use Illuminate\Support\Facades\Config;

class ExternalLoginController extends Controller
{
    /** @var \Sausin\Signere\ExternalLogin */
    protected $extLogin;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\ExternalLogin $extLogin
     * @return void
     */
    public function __construct(ExternalLogin $extLogin)
    {
        parent::__construct();

        $this->extLogin = $extLogin;
    }

    /**
     * Invalidates a login request.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     * @todo this should be accessible only by the bidder / auth user
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, ['request_id' => 'required|string|size:36']);

        return $this->extLogin->invalidateLogin(['RequestId' => $request->request_id])
                ->getBody()
                ->getContents();
    }
}
