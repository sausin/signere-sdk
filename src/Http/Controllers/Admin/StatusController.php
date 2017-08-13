<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\Status;
use Illuminate\Http\Request;
use Sausin\Signere\Http\Controllers\Controller;

class StatusController extends Controller
{
    /** @var \Sausin\Signere\Status */
    protected $status;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\Status $status
     * @return void
     */
    public function __construct(Status $status)
    {
        parent::__construct();

        $this->status = $status;
    }

    /**
     * Returns the UTC time of the Signere server.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->status->getServerTime()
                ->getBody()
                ->getContents();
    }

    /**
     * Check if Signere service is operational.
     *
     * @param  string $message
     * @return \Illuminate\Http\Response
     */
    public function show(string $message)
    {
        return $this->status->getServerStatus($message)
                ->getBody()
                ->getContents();
    }
}
