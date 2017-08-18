<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\DocumentFile;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class DocumentFileControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();
        
        m::close();
    }

    /** @test */
    public function an_admin_can_get_a_signed_pdf()
    {
        $df = m::mock(DocumentFile::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($df, 'getSignedPdf'));

        $df->shouldReceive('getSignedPdf')
                ->once()
                ->with($documentId = str_random(10))
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentFile::class, $df);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/file/' . $documentId)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_temporary_link()
    {
        $df = m::mock(DocumentFile::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($df, 'temporaryUrl'));

        $body = [
            'doc_id' => $doc_id = str_random(36),
            'file_type' => $file_type = 'PDF',
            'expires' => $expires = '2016-07-26T17:29:30'
        ];

        $df->shouldReceive('temporaryUrl')
                ->once()
                ->withArgs([$doc_id, $file_type, m::type(Carbon::class)])
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentFile::class, $df);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/file', $body)
            ->assertStatus(200);
    }
}
