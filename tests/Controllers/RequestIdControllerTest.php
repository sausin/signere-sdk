<?php

namespace Sausin\Signere\Tests\Controllers;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Headers;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\RequestId;
use Illuminate\Support\Facades\Config;

class RequestIdControllerTest extends AbstractControllerTest
{
    /** @test */
    public function a_guest_can_create_a_login_request()
    {
        $signereRequest = m::mock(RequestId::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($signereRequest, 'create'));

        $signereRequest->shouldReceive('create')->once()->andReturn(new Response(200, [], ''));

        $this->app->instance(RequestId::class, $signereRequest);

        $data = [
            'session_id' => null,
            'person_number' => false,
            'language' => 'EN',
            'page_title' => str_random(10),
            'iframe' => false,
            'web_messaging' => true
        ];

        $this->json('POST', '/signere/guest/login', $data)
            ->assertStatus(422)
            ->assertJsonFragment(['session_id']);

        $data['session_id'] = str_random(10);

        $this->json('POST', '/signere/guest/login', $data)
            ->assertStatus(200);
    }

    /** @test */
    public function a_bidder_can_logout()
    {
        $signereRequest = m::mock(RequestId::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($signereRequest, 'invalidate'));

        $data = ['request_id' => null];
        $wrongRequestId = str_random(90);
        $requestId = str_random(120);

        $signereRequest->shouldReceive('invalidate')
                        ->once()
                        ->with($requestId)
                        ->andReturn(new Response(200, [], ''));

        $this->app->instance(RequestId::class, $signereRequest);

        // this will fail a check as there is no
        // request id set on the request
        $this->actingAs(new Fakes\Bidder)
            ->json('DELETE', '/signere/bidder/logout', $data)
            ->assertStatus(422)
            ->assertJsonFragment(['request_id']);

        $data['request_id'] = $wrongRequestId;

        // request Id should be minimum 100 characters
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

    /** @test */
    public function a_bidder_can_check_status_of_his_login()
    {
        $signereRequest = m::mock(RequestId::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($signereRequest, 'check'));

        $requestId = str_random(10);
        $signereRequest->shouldReceive('check')
                        ->once()
                        ->with($requestId)
                        ->andReturn(new Response(200, [], ''));

        $this->app->instance(RequestId::class, $signereRequest);

        // this will fail a check as there is no
        // request id set on the request
        $this->actingAs(new Fakes\Bidder)
            ->json('GET', '/signere/bidder/check/123_234')
            ->assertStatus(404);

        // and now this should go through
        $this->actingAs(new Fakes\Bidder)
            ->json('GET', '/signere/bidder/check/' . $requestId)
            ->assertStatus(200);
    }
}
