<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\Invoice;
use Sausin\Signere\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    /** @var \Sausin\Signere\Invoice */
    protected $invoice;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        parent::__construct();

        $this->invoice = $invoice;
    }

    /**
     * Returns a list of invoice transactions for the given month.
     *
     * @param  int    $year
     * @param  int    $month
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $year, int $month)
    {
        return $this->invoice->get($year, $month)
                ->getBody()
                ->getContents();
    }
}
