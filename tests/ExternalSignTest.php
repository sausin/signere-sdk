<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Sausin\Signere\ExternalSign;
use Illuminate\Support\Facades\Config;

class ExternalSignTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/externalsign';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_the_signing_url()
    {
        $detail = '{"Signed":true,"CreatedSigneeRefs":[{"SigneeRefId":"8210132929654b8eb02bd7e33250c069","OriginatorUniqueRef":"8210132929654b8eb02bd7e33250c069","SignUrl":"Https://www.signere.no/signereexternal/82101329-2965-4b8e-b02b-d7e33250c069/fasdfasdfasdfsfasdf=="},{"SigneeRefId":"8210132929654b8eb02bd7e33250c069","OriginatorUniqueRef":"8210132929654b8eb02bd7e33250c069","SignUrl":"Https://www.signere.no/signereexternal/82101329-2965-4b8e-b02b-d7e33250c069/fasdfasdfasdfsfasdf=="}]}';

        $documentId = str_random(10);
        $url = sprintf('%s/%s', $this->uri, $documentId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url, [], true])->andReturn([]);

        $es = new ExternalSign($this->makeClient($detail), $this->headers);
        $response = $es->getUrlForSign($documentId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_the_viewer_applet_url()
    {
        $detail = 'https://..';

        $documentId = str_random(10);
        $params = ['Domain' => 'https://', 'Language' => 'gb'];
        $url = sprintf(
            '%s/ViewerUrl/%s/%s/%s',
            $this->uri,
            $documentId,
            $params['Domain'], 
            $params['Language']
        );

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url, [], true])->andReturn([]);

        $es = new ExternalSign($this->makeClient($detail), $this->headers);
        $response = $es->getUrlForApplet($documentId, $params);

        $this->assertEquals($detail, $response->getBody()->getContents());

        $this->expectException('BadMethodCallException');
        $response = $es->getUrlForApplet($documentId, []);
    }

    /** @test */
    public function it_can_get_the_session_status()
    {
        $detail = '{"UserAborted":true,"ErrorCode":"Unkown","ErrorMessage":"Unkown or blank","Signed":true}';

        $signeeRefId = str_random(10);
        $url = sprintf('%s/BankIDMobileSign/Status/%s', $this->uri, $signeeRefId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $es = new ExternalSign($this->makeClient($detail), $this->headers);
        $response = $es->getSessionStatus($signeeRefId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_an_external_sign_request()
    {
        $detail = '{"DocumentId":"8210132929654b8eb02bd7e33250c069}';

        $subItem = [
            'UniqueRef' => '8210132929654b8eb02bd7e33250c069', 
            'FirstName' => 'Kari', 
            'LastName' => 'Normann', 
            'Email' => 'kari@normann.no'
        ];
        $body = [
            'Description' => 'This document is a sales contract',
            'ExternalDocumentId' => '1234',
            'FileContent' => 'JVBERi0x....LjYNJeLjz9M',
            'Filename' => 'contract.pdf',
            'ReturnUrlError' => 'https://',
            'ReturnUrlSuccess' => 'https://',
            'ReturnUrlUserAbort' => 'https://',
            'SigneeRefs' => [$subItem, $subItem],
            'Title' => 'Sales contract',
        ];

        $url = $this->uri;

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);

        $es = new ExternalSign($this->makeClient($detail), $this->headers);
        $response = $es->createRequest($body);

        $this->assertEquals($detail, $response->getBody()->getContents());

        $this->expectException('BadMethodCallException');
        $es->createRequest(array_slice($body, 2));
    }

    /** @test */
    public function it_can_create_an_app_url()
    {
        $detail = '';

        $body = [
            'DocumentId' => '1D4C883ED2CE48C8B4A9A08A00D4D3A4',
            'SigneeRefId' => '1D4C883ED2CE48C8B4A9A08A00D4D3A4',
            'UserAgent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0_3 like Mac OS X)'
        ];

        $url = sprintf('%s/BankIDAppUrl', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['PUT', $url, $body])->andReturn([]);

        $es = new ExternalSign($this->makeClient($detail), $this->headers);
        $response = $es->createAppUrl($body);

        $this->assertEquals($detail, $response->getBody()->getContents());

        $this->expectException('BadMethodCallException');
        $es->createRequest(array_slice($body, 2));
    }

    /** @test */
    public function it_can_start_a_bankid_mobile_session()
    {
        $detail = '';

        $body = [
            'DateOfBirth' => '071283',
            'DocumentId' => '1D4C883ED2CE48C8B4A9A08A00D4D3A4',
            'Mobile' => '+4799716935',
            'SigneeRefId' => '1D4C883ED2CE48C8B4A9A08A00D4D3A4'
        ];

        $url = sprintf('%s/BankIDMobileSign', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['PUT', $url, $body])->andReturn([]);

        $es = new ExternalSign($this->makeClient($detail), $this->headers);
        $response = $es->startMobile($body);

        $this->assertEquals($detail, $response->getBody()->getContents());

        $this->expectException('BadMethodCallException');
        $es->createRequest(array_slice($body, 2));
    }
}