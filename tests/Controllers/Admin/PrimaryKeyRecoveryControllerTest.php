<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\ApiKey;
use Sausin\Signere\Headers;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class PrimaryKeyRecoveryControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function an_admin_can_request_recover_of_primary_key()
    {
        $key = m::mock(ApiKey::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($key, 'recoverPrimary'));

        $body1 = [
            'phone_number' => $phone = '+4712345678',
            'message' => $message = 'Your OTP code is {0}:',
        ];
        $body2 = [
            'MobileNumber' => $phone,
            'ProviderID' => 'id',
            'SmsMessage' => $message
        ];

        $key->shouldReceive('recoverPrimary')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(ApiKey::class, $key);

        $this->actingAs(new User)
            ->json('PATCH', '/signere/admin/keys/primary', $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_request_recover_of_primary_key_without_message_suggestion()
    {
        $key = m::mock(ApiKey::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($key, 'recoverPrimary'));

        $body1 = ['phone_number' => $phone = '+4712345678'];
        $body2 = ['MobileNumber' => $phone, 'ProviderID' => 'id'];

        $key->shouldReceive('recoverPrimary')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(ApiKey::class, $key);

        $this->actingAs(new User)
            ->json('PATCH', '/signere/admin/keys/primary', $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_confirm_request_for_primary_key_recovery()
    {
        $key = m::mock(ApiKey::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($key, 'createPrimary'));

        $body = ['otp' => $otp = '123456'];

        $key->shouldReceive('createPrimary')
                ->once()
                ->withArgs(['id', (int) $otp])
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(ApiKey::class, $key);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/keys/primary', $body)
            ->assertStatus(200);
    }
}
