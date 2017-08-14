<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Headers;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\RequestId;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class RequestIdControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function an_admin_can_request_for_statistics_with_params()
    {
        $request = m::mock(RequestId::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($request, 'getDetails'));

        $body = ['request_id' => $rid = str_random(10), 'metadata' => 1];

        $request->shouldReceive('getDetails')
            ->once()
            ->withArgs([$rid, 1])
            ->andReturn(new Response(200, [], ''));

        $this->app->instance(RequestId::class, $request);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/requestDetails', $body)
            ->assertStatus(200);
    }
}
