<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Sausin\Signere\DocumentJob;
use Sausin\Signere\Http\Controllers\Controller;

class DocumentJobController extends Controller
{
    /** @var \Sausin\Signere\DocumentJob */
    protected $dj;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\DocumentJob $dj
     * @return void
     */
    public function __construct(DocumentJob $dj)
    {
        parent::__construct();

        $this->dj = $dj;
    }

    /**
     * Retrieves a document job in the form of a response
     * object containing the document job parameters.
     *
     * @param  string $jobId
     * @return \Illuminate\Http\Response
     */
    public function show(string $jobId)
    {
        return $this->dj->get($jobId)
                ->getBody()
                ->getContents();
    }

    /**
     * Retrieves a document job in the form of a response
     * object containing the document job parameters.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'bail|required|email|max:255',
            'mobile' => [
                'bail',
                'nullable',
                'string',
                'regex:/^\+47\d{8}$/i',
            ],
            'name' => 'bail|nullable|string|min:1|max:255',
            'phone' => [
                'bail',
                'required',
                'string',
                'regex:/^\+47\d{8}$/i',
            ],
            'url' => 'bail|nullable|url',
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'email' => 'Contact_Email',
            'mobile' => 'Contact_Mobile',
            'name' => 'Contact_Name',
            'phone' => 'Contact_Phone',
            'url' => 'Contact_Url',
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        return $this->dj->create($body)
                ->getBody()
                ->getContents();
    }
}
