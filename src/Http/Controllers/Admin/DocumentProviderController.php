<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Sausin\Signere\DocumentProvider;
use Sausin\Signere\Http\Controllers\Controller;

class DocumentProviderController extends Controller
{
    /** @var \Sausin\Signere\DocumentProvider */
    protected $dp;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\DocumentProvider $dp
     * @return void
     */
    public function __construct(DocumentProvider $dp)
    {
        parent::__construct();

        $this->dp = $dp;
    }

    /**
     * Retrieves a document provider account.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->dp->getProviderAccount(Config::get('signere.id'))
                ->getBody()
                ->getContents();
    }

    /**
     * Retrieves usage for this account.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return $this->dp->getUsage(Config::get('signere.id'))
                ->getBody()
                ->getContents();
    }

    /**
     * Creates a new document provider account
     * for submitting documents.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //
        //
        //
        return $this->dp->create($body)
                ->getBody()
                ->getContents();
    }

    /**
     * Updates the information stored in a given
     * document provider account.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        //
        //
        //
        return $this->dp->update($body)
                ->getBody()
                ->getContents();
    }
}
