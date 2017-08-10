<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Carbon\Carbon;
use Sausin\Signere\ApiKey;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Config;

class ApiKeyTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/ApiToken';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_renew_a_primary_key()
    {
        $detail = '';

        $key = str_random(10);
        $url = sprintf('%s/RenewPrimaryKey?OldPrimaryKey=%s', $this->uri, $key);

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, [], true])->andReturn([]);

        $ak = new ApiKey($this->makeClient($detail), $this->headers);
        $response = $ak->renewPrimary($key);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_renew_a_secondary_key()
    {
        $detail = '';

        $key = str_random(10);
        $url = sprintf('%s/RenewSecondaryKey?OldSecondaryKey=%s', $this->uri, $key);

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, [], true])->andReturn([]);

        $ak = new ApiKey($this->makeClient($detail), $this->headers);
        $response = $ak->renewSecondary($key);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_a_primary_key()
    {
        $detail = '';

        $providerId = str_random(10);
        $otp = rand(100000, 100010);
        $url = sprintf(
            '%s/OTP/RenewPrimaryKeyStep2/Provider/%s/OTPCode/%s',
            $this->uri,
            $providerId,
            $otp
        );

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, [], false])->andReturn([]);

        $ak = new ApiKey($this->makeClient($detail), $this->headers);
        $response = $ak->createPrimary($providerId, $otp);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_request_renewal_of_a_lost_primary_key()
    {
        $detail = '';

        $body = [
            'MobileNumber' => '+4799775533',
            'ProviderID' => 'EA522127D971420595EFA08A00D4D3AE',
            'SmsMessage' => 'One time code for new API key is: {0}'
        ];
        $url = sprintf('%s/OTP/RenewPrimaryKeyStep1', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['PUT', $url, $body, false])->andReturn([]);

        $ak = new ApiKey($this->makeClient($detail), $this->headers);
        $response = $ak->recoverPrimary($body);

        $this->assertEquals($detail, $response->getBody()->getContents());

        $body['SmsMessage'] = 'dkjsklj';
        $this->expectException('InvalidArgumentException');
        $ak->recoverPrimary($body);
    }
}
