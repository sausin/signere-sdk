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
     * @return void
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
     * Retrieves the document with the given ID.
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
            'doc_id' => 'bail|required|string|size:36',
            'file_type' => 'bail|nullable|string|in:SDO,PDF,SIGNED_PDF,MOBILE_SDO,XML',
            'expires' => 'bail|nullable|date',
        ]);

        $type = $request->has('file_type') ? $request->file_type : 'PDF';
        $expires = $request->has('expires') ? Carbon::parse($request->expires) : null;

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
