<?php

namespace Sausin\Signere\Tests\Controllers;

use Mockery as m;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\ExternalLogin;

class ExternalLoginControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function a_bidder_can_logout()
    {
        $extLogin = m::mock(ExternalLogin::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($extLogin, 'invalidateLogin'));

        $data = ['request_id' => null];
        $requestId = str_random(36);

        $extLogin->shouldReceive('invalidateLogin')
                        ->once()
                        ->with(['RequestId' => $requestId])
                        ->andReturn(new Response(200, [], ''));

        $this->app->instance(ExternalLogin::class, $extLogin);

        // this will fail a check as there is no
        // request id set on the request
        $this->actingAs(new Fakes\Bidder)
            ->json('DELETE', '/signere/bidder/logout', $data)
            ->assertStatus(422)
            ->assertJsonFragment(['request_id']);

        $data['request_id'] = $requestId;

        // and now this should go through
        $this->actingAs(new Fakes\Bidder)
            ->json('DELETE', '/signere/bidder/logout', $data)
            ->assertStatus(200);
    }
}
