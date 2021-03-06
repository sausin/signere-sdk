<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use Sausin\Signere\Status;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class StatusControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function an_admin_can_request_for_server_timestamp()
    {
        $stats = m::mock(Status::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($stats, 'getServerTime'));

        $stats->shouldReceive('getServerTime')->once()->andReturn(new Response(200, [], ''));

        $this->app->instance(Status::class, $stats);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/status')
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_request_for_server_status()
    {
        $stats = m::mock(Status::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($stats, 'getServerStatus'));

        $stats->shouldReceive('getServerStatus')
            ->once()
            ->with($message = str_random(10))
            ->andReturn(new Response(200, [], ''));

        $this->app->instance(Status::class, $stats);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/status/'.$message)
            ->assertStatus(200);
    }
}
