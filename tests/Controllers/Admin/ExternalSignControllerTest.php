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
        $extSign = m::mock(ExternalSign::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($extSign, 'getUrlForSign'));

        $documentId = str_random(36);

        $extSign->shouldReceive('getUrlForSign')
                ->once()
                ->with($documentId)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(ExternalSign::class, $extSign);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/externalSign/' . $documentId)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_get_urls_for_a_viewer_applet_for_iframe()
    {
        $extSign = m::mock(ExternalSign::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($extSign, 'getUrlForApplet'));

        $extSign->shouldReceive('getUrlForApplet')
                ->once()
                ->withArgs([$documentId = str_random(36), ['Domain' => $domain = 'site.name', 'Language' =>  $lang = 'EN']])
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(ExternalSign::class, $extSign);

        $this->actingAs(new User)
            ->json('GET', sprintf('/signere/admin/externalSign/%s/%s/%s', $documentId, $domain, $lang))
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_new_external_sign_request()
    {
        $extSign = m::mock(ExternalSign::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($extSign, 'createRequest'));

        $body1 = [
            'description' => $description = str_random(30),
            'ext_doc_id' => $ext_doc_id = str_random(10),
            'file_content' => $file_content = str_random(60),
            'filename' => $filename = str_random(10),
            'signee_refs' => [
                [
                    'unique_ref' => $ur1 = str_random(36),
                    'first_name' => $fn1 = str_random(10),
                    'last_name' => $ln1 = str_random(10),
                    'email' => $e1 = 'u@five.com',
                ],
                [
                    'unique_ref' => $ur2 = str_random(36),
                    'first_name' => $fn2 = str_random(10),
                    'last_name' => $ln2 = str_random(10),
                    'email' => $e2 = 'u@three.com',
                ],
            ],
            'title' => $title = str_random(15),
        ];

        $body2 = [
            'Description' => $description,
            'ExternalDocumentId' => $ext_doc_id,
            'FileContent' => $file_content,
            'Filename' => $filename,
            'Title' => $title,
            'SigneeRefs' => [
                [
                    'UniqueRef' => $ur1,
                    'FirstName' => $fn1,
                    'LastName' => $ln1,
                    'Email' => $e1
                ],
                [
                    'UniqueRef' => $ur2,
                    'FirstName' => $fn2,
                    'LastName' => $ln2,
                    'Email' => $e2
                ]
            ],
            'ReturnUrlError' => 'https://abc.com/auth/error?status=[0]',
            'ReturnUrlSuccess' => 'https://abc.com/auth/success?requestid=[1]&externalid=[2]',
            'ReturnUrlUserAbort' => 'https://abc.com/auth/abort?requestid=[1]&externalid=[2]'
        ];

        $extSign->shouldReceive('createRequest')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(ExternalSign::class, $extSign);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/externalSign', $body1)
            ->assertStatus(200);
    }
}
