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

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

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

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $es = new ExternalSign($this->makeClient($detail), $this->headers);
        $response = $es->getUrlForApplet($documentId, $params);

        $this->assertEquals($detail, $response->getBody()->getContents());

        $this->expectException('BadMethodCallException');
        $es->getUrlForApplet($documentId, []);
    }
}