<?php

namespace Sausin\Signere\Tests\Controllers;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Invoice;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;

class InvoiceControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();
        
        m::close();
    }
    
    /** @test */
    public function an_admin_can_get_invoice_details()
    {
        $invoice = m::mock(Invoice::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($invoice, 'get'));

        $invoice->shouldReceive('get')
                ->once()
                ->withArgs([2016, 9])
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Invoice::class, $invoice);

        $this->actingAs(new Fakes\User)
            ->json('GET', '/signere/admin/invoice/2016/9')
            ->assertStatus(200);
    }
}
