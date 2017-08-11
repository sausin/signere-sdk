<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\ApiKey;
use Illuminate\Http\Request;
use Sausin\Signere\Http\Controllers\Controller;

class PrimaryKeyRecoveryController extends Controller
{
    /**
     * Generates a new primary key and returns it.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'provider' => 'required|string|size:36',
            'otp' => 'required|numeric',
        ]);
        
        return (new ApiKey)->createPrimary($request->provider, $request->otp)
                ->getBody()
                ->getContents();
    }

    /**
     * Request new key generation.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'phone_number' => [
                'required',
                'string',
                'regex:/^\+47\d{8}$/i'
            ],
            'provider' => 'required|string|size:36',
            'message' => [
                'string',
                'nullable',
                'regex:/\{0\}/i'
            ]
        ]);

        // create the body
        $body = [
            'MobileNumber' => $request->phone_number,
            'ProviderID' => $request->provider,
            'SmsMessage' => $request->message
        ];

        if (empty($request->message)) {
            unset($body['SmsMessage']);
        }

        return (new ApiKey)->recoverPrimary($body)
                ->getBody()
                ->getContents();
    }
}
