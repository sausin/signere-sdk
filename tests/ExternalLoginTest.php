<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Sausin\Signere\ExternalLogin;

class ExternalLoginTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/ExternalLogin';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_login_info()
    {
        $detail = '{"BankidPID":"9578-6000-4-48855","FullName":"Rune Synnevåg","SocSec":"23071212345","UserAgent":"WIN","IPAddress":"192.168.1.0","OperationStatus":"LOGIN","BankIdType":"APPLET"}';

        $requestId = str_random(10);
        $url = sprintf('%s/%s', $this->uri, $requestId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $el = new ExternalLogin($this->makeClient($detail), $this->headers);
        $response = $el->getLoginInfo($requestId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_app_launch_uri()
    {
        $detail = '{"AppLaunchURI": "no.bankid.app://?sid=asfasdfasdfasdfasdf&URL=customappuri://appparams?id=1","RequestId": "00000000000000000000000000000000"}';

        $body = ['ReturnUrl' => 'https://', 'ExternalId' => 'skldjskldjaskj'];
        $url = sprintf('%s/AppLogin', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);

        $el = new ExternalLogin($this->makeClient($detail), $this->headers);
        $response = $el->createAppLaunchUri($body);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_mobile_login()
    {
        $detail = '{"RequestId": "fb9efc8778c5440ebd8094cd745f1e09","MerchantRef": "SNILL BANK"}';

        $body = ['DateOfBirth' => '071283', 'Mobile' => '918121234'];
        $url = sprintf('%s/BankIDMobileLogin/Create', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);

        $el = new ExternalLogin($this->makeClient($detail), $this->headers);
        $response = $el->createMobile($body);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_start_mobile_session_or_create_it()
    {
        $detail = '';

        $body1 = [];
        $body2 = ['RequestId' => 'fb9efc8778c5440ebd8094cd745f1e09'];
        $url = sprintf('%s/BankIDMobileLogin/Start', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body1])->andReturn([]);
        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body2])->andReturn([]);

        $el = new ExternalLogin($this->makeClient($detail, 2), $this->headers);

        // empty input means it should create a new session
        $response = $el->startMobileSession();
        $this->assertEquals($detail, $response->getBody()->getContents());

        // empty input means it should start session with id
        $response = $el->startMobileSession($body2);
        $this->assertEquals($detail, $response->getBody()->getContents());

        $this->expectException('UnexpectedValueException');
        $el->startMobileSession(['Some' => 'Array']);
    }

    /** @test */
    public function it_can_invalidate_a_session()
    {
        $detail = '';

        $body = ['RequestId' => '9578-6000-4-48855'];
        $url = sprintf('%s/InvalidateLogin', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['PUT', $url, $body])->andReturn([]);

        $el = new ExternalLogin($this->makeClient($detail), $this->headers);

        // empty input means it should start session with id
        $response = $el->invalidateLogin($body);
        $this->assertEquals($detail, $response->getBody()->getContents());

        $this->expectException('BadMethodCallException');
        $el->invalidateLogin(['Some' => 'Array']);
    }
}
