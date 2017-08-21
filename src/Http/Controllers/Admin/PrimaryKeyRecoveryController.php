<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Http\Controllers\Controller;

class PrimaryKeyRecoveryController extends Controller
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
     * Generates a new primary key and returns it.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['otp' => 'required|numeric']);

        return $this->key->createPrimary(Config::get('signere.id'), (int) $request->otp)
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
                'regex:/^\+47\d{8}$/i',
            ],
            'message' => [
                'string',
                'nullable',
                'regex:/\{0\}/i',
            ],
        ]);

        // create the body
        $body = [
            'MobileNumber' => $request->phone_number,
            'ProviderID' => Config::get('signere.id'),
            'SmsMessage' => $request->message,
        ];

        if (empty($request->message)) {
            unset($body['SmsMessage']);
        }

        return $this->key->recoverPrimary($body)
                ->getBody()
                ->getContents();
    }
}
