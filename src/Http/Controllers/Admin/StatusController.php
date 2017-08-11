<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\Status;
use Illuminate\Http\Request;
use Sausin\Signere\Http\Controllers\Controller;

class StatusController extends Controller
{
    /**
     * Returns the UTC time of the Signere server.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return (new Status)->getServerTime()
                ->getBody()
                ->getContents();
    }

    /**
     * Check if Signere service is operational.
     *
     * @param  string $string
     * @return \Illuminate\Http\Response
     */
    public function show(string $string)
    {
        return (new Status)->getServerStatus($string)
                ->getBody()
                ->getContents();
    }
}
