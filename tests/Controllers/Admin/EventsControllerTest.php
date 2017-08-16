<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Events;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class EventsControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();
        
        m::close();
    }
    
    /** @test */
    public function an_admin_can_get_events_details()
    {
        $events = m::mock(Events::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($events, 'getEncryptionKey'));

        $events->shouldReceive('getEncryptionKey')
                ->once()
                ->with()
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Events::class, $events);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/events')
            ->assertStatus(200);
    }
}
