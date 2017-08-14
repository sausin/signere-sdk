<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Sausin\Signere\ExternalSign;
use Sausin\Signere\Http\Controllers\Controller;

class ExternalSignController extends Controller
{
    /** @var \Sausin\Signere\ExternalSign */
    protected $extSign;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\ExternalSign $extSign
     * @return void
     */
    public function __construct(ExternalSign $extSign)
    {
        parent::__construct();

        $this->extSign = $extSign;
    }

    /**
     * Get the URLs to sign the Document.
     *
     * @param  string $documentId
     * @return \Illuminate\Http\Response
     */
    public function index(string $documentId)
    {
    }

    /**
     * Get the URLs to view a viewerapplet in a iFrame on your site.
     *
     * @param  string $documentId
     * @param  string $domain
     * @param  string $language
     * @return \Illuminate\Http\Response
     */
    public function show(string $documentId, string $domain, string $language)
    {
    }

    /**
     * Creates an externalsign request.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|string|size:36',
            'ext_doc_id' => 'required|string|min:1|max:255',
            'file_content' => 'required|string',
            'filename' => 'required|string|min:1|max:255',
            // unique ref is nothing but the signee ref returned
            // by signere when a receiver is created
            'signee_refs.*.unique_ref' => 'required|string|size:36',
            'signee_refs.*.first_name' => 'required|string|min:1|max:255',
            'signee_refs.*.last_name' => 'required|string|min:1|max:255',
            'signee_refs.*.email' => 'required|email|min:1|max:255',
            'title' => 'required|string|min:1|max:255',
        ]);

        // this is used to only set the keys which have been sent in
        $body['Description'] = $request->description;
        $body['ExternalDocumentId'] = $request->ext_doc_id;
        $body['FileContent'] = $request->file_content;
        $body['Filename'] = $request->filename;
        $body['Title'] = $request->title;

        // populate the signee references
        foreach ($request->signee_refs as $signee) {
            // append this to the body
            $body['SigneeRefs'][] = [
                'UniqueRef' => $signee->unique_ref,
                'FirstName' => $signee->first_name,
                'LastName' => $signee->last_name,
                'Email' => $signee->email
            ];
        }

        // populate the callback URLs
        $body['ReturnUrlError']  = Config::get('signere.sign_error_url');
        $body['ReturnUrlSuccess'] = Config::get('signere.sign_success_url');
        $body['ReturnUrlUserAbort'] = Config::get('signere.sign_cancel_url');

        return $this->extSign->createRequest($body)
                ->getBody()
                ->getContents();
    }
}
