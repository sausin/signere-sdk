<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\ExternalSign;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class ExternalSignControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();
        
        m::close();
    }
    
    /** @test */
    public function an_admin_can_get_urls_for_signing_of_a_document()
    {
        $message = m::mock(ExternalSign::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($message, 'getUrlForSign'));

        $documentId = str_random(36);

        $message->shouldReceive('getUrlForSign')
                ->once()
                ->with($documentId)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(ExternalSign::class, $message);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/externalSign/' . $documentId)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_get_urls_for_a_viewer_applet_for_iframe()
    {
        $message = m::mock(ExternalSign::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($message, 'getUrlForApplet'));

        $message->shouldReceive('getUrlForApplet')
                ->once()
                ->withArgs([$documentId = str_random(36), ['Domain' => $domain = 'site.name', 'Language' =>  $lang = 'EN']])
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(ExternalSign::class, $message);

        $this->actingAs(new User)
            ->json('GET', sprintf('/signere/admin/externalSign/%s/%s/%s', $documentId, $domain, $lang))
            ->assertStatus(200);
    }
}
