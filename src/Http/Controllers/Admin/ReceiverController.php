<?php

namespace Sausin\Signere\Http\Controllers;

use Illuminate\Http\Request;
use Sausin\Signere\Receiver;

class ReceiverController extends Controller
{
    public function index(string $providerId)
    {
    }

    public function show(string $providerId, string $receiverId)
    {
    }

    /**
     * Create a receiver.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'sometimes|email|nullable',
            'company_name' => 'sometimes|string|nullable',
            'org_no' => 'sometimes|string|nullable',
            'phone_number' => 'sometimes|string|nullable'
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'first_name' => 'FirstName',
            'last_name' => 'LastName',
            'email' => 'Email',
            'company_name' => 'CompanyName',
            'org_no' => 'OrgNo',
            'phone_number' => 'Mobile'
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        return (new Receiver)->create($body)
                ->getBody()
                ->getContents();
    }

    public function destroy()
    {
    }
}
