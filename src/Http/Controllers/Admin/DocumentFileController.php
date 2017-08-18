<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Sausin\Signere\DocumentFile;
use Sausin\Signere\Http\Controllers\Controller;

class DocumentFileController extends Controller
{
    /** @var \Sausin\Signere\DocumentFile */
    protected $df;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\DocumentFile $df
     * @return void
     */
    public function __construct(DocumentFile $df)
    {
        parent::__construct();

        $this->df = $df;
    }

    /**
     * Returns the signed pdf document as a file.
     *
     * @param  string $documentId
     * @return \Illuminate\Http\Response
     */
    public function show(string $documentId)
    {
        return $this->df->getSignedPdf($documentId)
                ->getBody()
                ->getContents();
    }

    /**
     * Creates a temporary url to a document.
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

        return $this->df->temporaryUrl($request->doc_id, $type, $expires)
                ->getBody()
                ->getContents();
    }
}
