<?php

namespace Sausin\Signere\Http\Controllers;

use Illuminate\Http\Request;
use Sausin\Signere\Receiver;

class ReceiverController extends Controller
{
    /**
     * Returns all receivers.
     *
     * @param  string $providerId
     * @return \Illuminate\Http\Response
     */
    public function index(string $providerId)
    {
        return (new Receiver)->get($providerId)
                    ->getBody()
                    ->getContents();
    }

    /**
     * Returns the given receiver.
     *
     * @param  string $providerId
     * @param  string $receiverId
     * @return \Illuminate\Http\Response
     */
    public function show(string $providerId, string $receiverId)
    {
        return (new Receiver)->get($providerId, $receiverId)
                ->getBody()
                ->getContents();
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

    /**
     * Deletes one or many receivers.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'provider_id' => 'required|string',
            'receiver_id' => 'sometimes|nullable|string'
        ]);

        // if a specific receiver is to be deleted
        if ($request->has('provider_id') && $request->has('receiver_id')) {
            return (new Receiver)->delete($request->provider_id, $request->receiver_id)
                    ->getBody()
                    ->getContents();
        }

        // if all receivers are to be deleted
        return (new Receiver)->deleteAll($request->provider_id)
                ->getBody()
                ->getContents();
    }
}
