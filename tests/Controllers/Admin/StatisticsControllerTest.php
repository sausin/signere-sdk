<?php

namespace Sausin\Signere\Tests\Controllers;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Headers;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\Statistics;

class StatisticsControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function an_admin_can_request_for_statistics_without_params()
    {
        $stats = m::mock(Statistics::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($stats, 'get'));

        $body = [];

        $stats->shouldReceive('get')
            ->once()
            ->withArgs([null, null, null, 'All'])
            ->andReturn(new Response(200, [], ''));

        $this->app->instance(Statistics::class, $stats);

        $this->actingAs(new Fakes\User)
            ->json('POST', '/signere/admin/statistics', $body)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_request_for_statistics_with_params()
    {
        $stats = m::mock(Statistics::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($stats, 'get'));

        $body = ['year' => 2016, 'month' => 12, 'day' => 31, 'status' => 'Changed'];

        $stats->shouldReceive('get')
            ->once()
            ->withArgs([2016, 12, 31, 'Changed'])
            ->andReturn(new Response(200, [], ''));

        $this->app->instance(Statistics::class, $stats);

        $this->actingAs(new Fakes\User)
            ->json('POST', '/signere/admin/statistics', $body)
            ->assertStatus(200);
    }
}
