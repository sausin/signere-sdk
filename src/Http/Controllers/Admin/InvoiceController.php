<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\Invoice;
use Illuminate\Http\Request;
use Sausin\Signere\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    /**
     * Returns a list of invoice transactions for the given month.
     * 
     * @param  int    $year
     * @param  int    $month
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $year, int $month)
    {
        return (new Invoice)->get($year, $month)
                ->getBody()
                ->getContents();
    }
}
