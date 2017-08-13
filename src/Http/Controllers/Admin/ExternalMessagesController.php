<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\Message;
use Illuminate\Http\Request;
use Sausin\Signere\Http\Controllers\Controller;

class ExternalMessagesController extends Controller
{
    /** @var \Sausin\Signere\Message */
    protected $message;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\Message $message
     * @return void
     */
    public function __construct(Message $message)
    {
        parent::__construct();

        $this->message = $message;
    }

    /**
     * Sends a message to an external person
     * with a link/URL to view a document.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'document_id' => 'required|string|size:36',
            'message' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => [
                'required',
                'regex:/^\+47\d{8}$/i'
            ],
            'signature' => 'required|string',
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'document_id' => 'DocumentID',
            'message' => 'EmailMessage',
            'email' => 'RecipientEmailAddress',
            'phone_number' => 'MobileNumber',
            'signature' => 'SenderSignature',
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        return $this->message->sendExternalMessage($body)
                ->getBody()
                ->getContents();
    }
}
