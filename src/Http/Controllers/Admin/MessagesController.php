<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\Message;
use Illuminate\Http\Request;
use Sausin\Signere\Http\Controllers\Controller;

class MessagesController extends Controller
{
    /** @var \Sausin\Signere\Message */
    protected $message;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\Message $message
     */
    public function __construct(Message $message)
    {
        parent::__construct();

        $this->message = $message;
    }

    /**
     * Retrieves a list of all the messages for
     * the given document id.
     *
     * @param  string $documentId
     * @return \Illuminate\Http\Response
     */
    public function index(string $documentId)
    {
        return $this->message->all($documentId)
                ->getBody()
                ->getContents();
    }

    /**
     * Gets details for a specific message.
     *
     * @param  string $messageId
     * @return \Illuminate\Http\Response
     */
    public function show(string $messageId)
    {
        return $this->message->get($messageId)
                ->getBody()
                ->getContents();
    }

    /**
     * Sends a message to all signees of a document.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'document_id' => 'required|string|size:36',
            'message' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'signature' => 'required|string',
            'signee_ref' => 'required|string|size:36',
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'document_id' => 'DocumentID',
            'message' => 'EmailMessage',
            'email' => 'RecipientEmailAddress',
            'signature' => 'SenderSignature',
            'signee_ref' => 'SigneeRef',
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        return $this->message->sendMessage($body)
                ->getBody()
                ->getContents();
    }

    /**
     * Sends a new message to the Signeeref if the first one failed.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'document_id' => 'required|string|size:36',
            'email' => 'nullable|email|max:255',
            'replace_email' => 'nullable|string',
            'signee_ref' => 'required|string|size:36',
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'document_id' => 'DocumentID',
            'email' => 'RecipientEmailAddress',
            'replace_email' => 'ReplaceEmail',
            'signee_ref' => 'SigneeRef',
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        return $this->message->sendNewMessage($body)
                ->getBody()
                ->getContents();
    }
}
