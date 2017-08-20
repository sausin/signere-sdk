<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use Carbon\Carbon;
use Sausin\Signere\Document;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class DocumentControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function an_admin_can_get_a_list_of_all_documents_for_a_job_id()
    {
        $d = m::mock(Document::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($d, 'getList'));

        $d->shouldReceive('getList')
                ->once()
                ->with($jobId = str_random(10))
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Document::class, $d);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/document/'.$jobId)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_get_a_document_sign_url()
    {
        $d = m::mock(Document::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($d, 'getSignUrl'));

        $d->shouldReceive('getSignUrl')
                ->once()
                ->withArgs([$docId = str_random(36), $signRefId = str_random(36)])
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Document::class, $d);

        $this->actingAs(new User)
            ->json('GET', sprintf('/signere/admin/document/%s/%s', $docId, $signRefId))
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_new_document()
    {
        $d = m::mock(Document::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($d, 'create'));

        $body1 = [
            'description' => $desc = str_random(30),
            'file_content' => $cont = str_random(30),
            'file_md5' => $md5 = str_random(32),
            'filename' => $name = str_random(30),
            'language' => $lang = 'EN',
            'sender_email' => $sem = 'test@ureach.coms',
            'sign_deadline' => $dead = '2016-11-23T23:34:12',
            'job_id' => $jobId = str_random(36),
            'title' => $title = str_random(30),
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
        ];

        $body2 = [
            'Description' => $desc,
            'FileContent' => $cont,
            'FileMD5CheckSum' => $md5,
            'FileName' => $name,
            'Language' => $lang,
            'SenderEmail' => $sem,
            'SignDeadline' => $dead,
            'SignJobId' => $jobId,
            'Title' => $title,
            'SigneeRefs' => [
                [
                    'SigneeRefId' => $ur1,
                    'FirstName' => $fn1,
                    'LastName' => $ln1,
                    'Email' => $e1,
                ],
                [
                    'SigneeRefId' => $ur2,
                    'FirstName' => $fn2,
                    'LastName' => $ln2,
                    'Email' => $e2,
                ],
            ],
        ];

        $d->shouldReceive('create')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Document::class, $d);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/document', $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_change_the_deadline_for_a_document()
    {
        $d = m::mock(Document::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($d, 'changeDeadline'));

        $body1 = [
            'new_deadline' => $nd = '2016-11-23T23:34:12',
            'notify_email' => $nemail = rand(0, 1),
            'notify_sms' => $nesms = rand(0, 1),
        ];

        $body2 = [
            'NewDeadline' => $nd,
            'NotifyEmail' => $nemail,
            'NotifySMS' => $nesms,
            'DocumentID' => $docId = str_random(36),
            'ProviderID' => 'id',
        ];

        $d->shouldReceive('changeDeadline')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Document::class, $d);

        $this->actingAs(new User)
            ->json('PATCH', '/signere/admin/document/'.$docId, $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_cancel_a_document()
    {
        $d = m::mock(Document::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($d, 'cancel'));

        $body1 = [
            'canceled_date' => $cd = '2016-11-23T23:34:12',
            'explanation' => $exp = str_random(30),
            'signature' => $sig = str_random(30),
        ];

        $body2 = [
            'Explanation' => $exp,
            'Signature' => $sig,
            'CanceledDate' => substr(Carbon::parse($cd)->setTimezone('UTC')->toIso8601String(), 0, 19),
            'DocumentID' => $docId = str_random(36),
        ];

        $d->shouldReceive('cancel')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Document::class, $d);

        $this->actingAs(new User)
            ->json('DELETE', '/signere/admin/document/'.$docId, $body1)
            ->assertStatus(200);
    }
}
