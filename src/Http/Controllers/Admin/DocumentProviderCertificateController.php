<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\DocumentProvider;
use Sausin\Signere\Http\Controllers\Controller;

class DocumentProviderCertificateController extends Controller
{
    /** @var \Sausin\Signere\DocumentProvider */
    protected $dp;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\DocumentProvider $dp
     */
    public function __construct(DocumentProvider $dp)
    {
        parent::__construct();

        $this->dp = $dp;
    }

    /**
     * Gets the expires date for your BankID certificate.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return $this->dp->getCertExpiry()
                ->getBody()
                ->getContents();
    }
}
