<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\DocumentProvider;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class DocumentProviderCertificateControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();
        
        m::close();
    }
    
    /** @test */
    public function an_admin_can_get_certificate_details()
    {
        $dp = m::mock(DocumentProvider::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($dp, 'getCertExpiry'));

        $dp->shouldReceive('getCertExpiry')
                ->once()
                ->with()
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentProvider::class, $dp);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/provider/certificate')
            ->assertStatus(200);
    }
}
