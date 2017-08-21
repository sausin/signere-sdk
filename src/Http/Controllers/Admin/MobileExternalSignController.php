<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Sausin\Signere\ExternalSign;
use Sausin\Signere\Http\Controllers\Controller;

class MobileExternalSignController extends Controller
{
    /** @var \Sausin\Signere\ExternalSign */
    protected $extSign;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\ExternalSign $extSign
     */
    public function __construct(ExternalSign $extSign)
    {
        parent::__construct();

        $this->extSign = $extSign;
    }

    /**
     * Get status of the BankID mobile Sign session.
     *
     * @param  string $signeeRefId
     * @return \Illuminate\Http\Response
     */
    public function show(string $signeeRefId)
    {
        return $this->extSign->getSessionStatus($signeeRefId)
                ->getBody()
                ->getContents();
    }

    /**
     * Starts a BankID mobile sign session for the given document.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'birth_date' => [
                'required',
                'string',
                'size:6',
                'regex:/^\d{6}$/i',
            ],
            'document_id' => 'required|string|size:36',
            'phone_number' => [
                'required',
                'regex:/^\+47\d{8}$/i',
            ],
            'signee_ref' => 'required|string|size:36',
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'birth_date' => 'DateOfBirth',
            'document_id' => 'DocumentID',
            'phone_number' => 'Mobile',
            'signee_ref' => 'SigneeRef',
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        return $this->extSign->startMobile($body)
                ->getBody()
                ->getContents();
    }
}
