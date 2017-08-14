<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\ApiKey;
use Sausin\Signere\Headers;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class PrimaryKeyRenewalControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function an_admin_can_request_for_primary_key_renewal()
    {
        $key = m::mock(ApiKey::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($key, 'renewPrimary'));

        $body = [];

        $key->shouldReceive('renewPrimary')
                ->once()
                ->with('primary_key')
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(ApiKey::class, $key);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/keys/primary/renew', $body)
            ->assertStatus(200);
    }
}
