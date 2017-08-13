<?php

namespace Sausin\Signere\Tests\Controllers;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Headers;
use Sausin\Signere\Receiver;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;

class ReceiverControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function an_admin_can_view_all_receivers()
    {
        $receiver = m::mock(Receiver::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($receiver, 'get'));

        $receiver->shouldReceive('get')
                ->once()
                ->with('id')
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Receiver::class, $receiver);

        $this->actingAs(new Fakes\User)
            ->json('GET', '/signere/admin/receivers')
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_view_details_of_a_particular_receiver()
    {
        $receiver = m::mock(Receiver::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($receiver, 'get'));

        $rId = str_random(10);
        $receiver->shouldReceive('get')
                ->once()
                ->withArgs(['id', $rId])
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Receiver::class, $receiver);

        $this->actingAs(new Fakes\User)
            ->json('GET', '/signere/admin/receivers/' . $rId)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_new_receiver()
    {
        $receiver = m::mock(Receiver::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($receiver, 'create'));

        $body1 = [
            'first_name' => $first = str_random(10),
            'last_name' => $last = str_random(10),
            'email' => $email = 'hey@you.run'
        ];
        $body2 = [
            'FirstName' => $first,
            'LastName' => $last,
            'Email' => $email,
        ];

        $receiver->shouldReceive('create')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Receiver::class, $receiver);

        $this->actingAs(new Fakes\User)
            ->json('POST', '/signere/admin/receivers', $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_delete_one_receiver()
    {
        $receiver = m::mock(Receiver::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($receiver, 'create'));

        $body1 = [];
        $body2 = ['receiver_id' => $rId = str_random(10)];

        $receiver->shouldReceive('delete')
                ->once()
                ->withArgs(['id', $rId])
                ->andReturn(new Response(200, [], ''));
        $receiver->shouldReceive('deleteAll')
                ->once()
                ->with('id')
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Receiver::class, $receiver);

        $this->actingAs(new Fakes\User)
            ->json('DELETE', '/signere/admin/receivers', $body1)
            ->assertStatus(200);

        $this->actingAs(new Fakes\User)
            ->json('DELETE', '/signere/admin/receivers', $body2)
            ->assertStatus(200);
    }
}
