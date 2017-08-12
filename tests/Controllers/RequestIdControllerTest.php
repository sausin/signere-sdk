<?php

namespace Sausin\Signere\Tests\Controllers;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Headers;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\RequestId;
use Sausin\Signere\Tests\MakeClient;
use Illuminate\Support\Facades\Config;

class RequestIdControllerTest extends AbstractControllerTest
{
    use MakeClient;

    /** @test */
    public function user_can_create_a_new_request()
    {
        $signereRequest = m::mock(RequestId::class);

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

        $this->actingAs(new Fakes\User)
            ->json('POST', '/signere/guest/auth', $data)
            ->assertStatus(422)
            ->assertJsonFragment(['session_id']);

        $data['session_id'] = str_random(10);

        $this->actingAs(new Fakes\User)
            ->json('POST', '/signere/guest/auth', $data)
            ->assertStatus(200);
    }
}
