<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Sausin\Signere\RequestId;
use Sausin\Signere\Http\Controllers\Controller;

class RequestIdController extends Controller
{
    /** @var \Sausin\Signere\RequestId */
    protected $signereRequest;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\RequestId $signereRequest
     */
    public function __construct(RequestId $signereRequest)
    {
        parent::__construct();

        $this->signereRequest = $signereRequest;
    }

    /**
     * Retrives a SignereID session to get the
     * information about the authorized user.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'request_id' => 'required|string',
            'metadata' => 'required|boolean',
        ]);

        return $this->signereRequest->getDetails($request->request_id, $request->metadata)
                ->getBody()
                ->getContents();
    }
}
