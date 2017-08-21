<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Sausin\Signere\Document;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Http\Controllers\Controller;

class DocumentController extends Controller
{
    /** @var \Sausin\Signere\Document */
    protected $d;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\Document $d
     */
    public function __construct(Document $d)
    {
        parent::__construct();

        $this->d = $d;
    }

    /**
     * Retrieves a list of documents corresponding to the jobId.
     *
     * @param  string $jobId
     * @return \Illuminate\Http\Response
     */
    public function index(string $jobId)
    {
        return $this->d->getList($jobId)
                ->getBody()
                ->getContents();
    }

    /**
     * Returns the url to sign the document for the given Signeeref
     * or the first Signeeref if not SigneerefId is specified.
     *
     * @param  string $documentId
     * @param  string $signeeRefId
     * @return \Illuminate\Http\Response
     */
    public function show(string $documentId, string $signeeRefId)
    {
        return $this->d->getSignUrl($documentId, $signeeRefId)
                ->getBody()
                ->getContents();
    }

    /**
     * Creates a new document to sign & returns
     * a document response object.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|string|min:1|max:255',
            'file_content' => 'required|string',
            'file_md5' => 'required|string|size:32',
            'filename' => 'required|string|min:1|max:255',
            'language' => 'required|string|in:EN,NO,SV,DA,FI',
            'sender_email' => 'sometimes|nullable|email',
            'sign_deadline' => 'sometimes|nullable|date',
            'job_id' => 'required|string|size:36',
            'title' => 'required|string|min:1|max:255',
            // unique ref is nothing but the signee ref returned
            // by signere when a receiver is created
            'signee_refs.*.unique_ref' => 'required|string|size:36',
            'signee_refs.*.first_name' => 'required|string|min:1|max:255',
            'signee_refs.*.last_name' => 'required|string|min:1|max:255',
            'signee_refs.*.email' => 'required|email|min:1|max:255',
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'ext_doc_id' => 'ExternalDocumentId',
            'description' => 'Description',
            'file_content' => 'FileContent',
            'file_md5' => 'FileMD5CheckSum',
            'filename' => 'FileName',
            'language' => 'Language',
            'sender_email' => 'SenderEmail',
            'sign_deadline' => 'SignDeadline',
            'job_id' => 'SignJobId',
            'title' => 'Title',
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        $body['SigneeRefs'] = [];

        // populate the signee references
        foreach ($request->signee_refs as $signee) {
            // append this to the body
            $body['SigneeRefs'][] = [
                'SigneeRefId' => $signee['unique_ref'],
                'FirstName' => $signee['first_name'],
                'LastName' => $signee['last_name'],
                'Email' => $signee['email'],
            ];
        }

        return $this->d->create($body)
                ->getBody()
                ->getContents();
    }

    /**
     * Changes the signature deadline for a given document.
     *
     * @param  Request $request
     * @param  string  $documentId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $documentId)
    {
        $this->validate($request, [
            'new_deadline' => 'bail|required|date',
            'notify_email' => 'bail|nullable|boolean',
            'notify_sms' => 'bail|nullable|boolean',
        ]);

        $body = [];

        // setup the body
        $body['NewDeadline'] = $request->new_deadline;
        $body['NotifyEmail'] = $request->has('notify_email') ? $request->notify_email : false;
        $body['NotifySMS'] = $request->has('notify_sms') ? $request->notify_sms : false;
        $body['DocumentID'] = $documentId;
        $body['ProviderID'] = Config::get('signere.id');

        return $this->d->changeDeadline($body)
                ->getBody()
                ->getContents();
    }

    /**
     * Changes the signature deadline for a given document.
     *
     * @param  Request $request
     * @param  string  $documentId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $documentId)
    {
        $this->validate($request, [
            'canceled_date' => 'bail|required|date',
            'explanation' => 'bail|nullable|string|max:255',
            'signature' => 'bail|nullable|string|max:150',
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'explanation' => 'Explanation',
            'signature' => 'Signature',
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        $body['CanceledDate'] = substr(
            Carbon::parse($request->canceled_date)->setTimezone('UTC')->toIso8601String(),
            0,
            19
        );
        $body['DocumentID'] = $documentId;

        return $this->d->cancel($body)
                ->getBody()
                ->getContents();
    }
}
