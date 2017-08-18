<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\DocumentJob;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class DocumentJobControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();
        
        m::close();
    }

    /** @test */
    public function an_admin_can_get_a_jobs_details()
    {
        $dj = m::mock(DocumentJob::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($dj, 'get'));

        $dj->shouldReceive('get')
                ->once()
                ->with($jobId = str_random(10))
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentJob::class, $dj);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/job/' . $jobId)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_new_job()
    {
        $dj = m::mock(DocumentJob::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($dj, 'create'));

        $body1 = [
            'email' => $email = 'random@mymail.stopped',
            'mobile' => $mobile = '+4712345678',
            'name' => $name = str_random(10),
            'phone' => $phone = '+4712345678',
            'url' => $url = 'https://random.pigs'
        ];

        $body2 = [
            'Contact_Email' => $email,
            'Contact_Mobile' => $mobile,
            'Contact_Name' => $name,
            'Contact_Phone' => $phone,
            'Contact_Url' => $url
        ];

        $dj->shouldReceive('create')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentJob::class, $dj);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/job', $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_job_with_only_required_details()
    {
        $dj = m::mock(DocumentJob::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($dj, 'create'));

        $body1 = [
            'email' => $email = 'random@mymail.stopped',
            'phone' => $phone = '+4712345678',
        ];

        $body2 = [
            'Contact_Email' => $email,
            'Contact_Phone' => $phone,
        ];

        $dj->shouldReceive('create')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentJob::class, $dj);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/job', $body1)
            ->assertStatus(200);
    }
}
