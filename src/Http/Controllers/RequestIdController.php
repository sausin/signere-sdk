<?php

namespace Sausin\Signere\Http\Controllers;

use Illuminate\Http\Request;
use Sausin\Signere\RequestId;
use Illuminate\Support\Facades\Config;

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
     * Check if a SignereID session is completed or not.
     *
     * @param  string $requestId
     * @return \Illuminate\Http\Response
     */
    public function show(string $requestId)
    {
        return $this->signereRequest->check($requestId)
                ->getBody()
                ->getContents();
    }

    /**
     * Creates a SignereID request, and returns a url.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'session_id' => 'required|string',
            'person_number' => 'required|boolean',
            'language' => 'required|string|size:2|in:EN,NO,SV,DA,FI',
            'page_title' => 'sometimes|string',
            'iframe' => 'required|string|in:true,false',
            'iframe_height' => 'sometimes|nullable|numeric',
            'web_messaging' => 'required|string|in:true,false'
        ]);

        $body = [
            'CancelUrl' => Config::get('signere.cancel_url'),
            'Domain' => Config::get('signere.domain'),
            'ErrorUrl' => Config::get('signere.error_url'),
            'Height' => $request->iframe_height ?: Config::get('signere.iframe_height'),
            'SuccessUrl' => Config::get('signere.success_url'),
            'ExternalReference' => $request->session_id,
            'IdentityProvider' => Config::get('signere.identity_provider'),
            'Language' => $request->language,
            'PageTitle' => $request->page_title,
            'UseIframe' => $request->iframe,
            'UseWebMessaging' => $request->web_messaging
        ];

        return $this->signereRequest->create($body)
                ->getBody()
                ->getContents();
    }
}
