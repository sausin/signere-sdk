<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Sausin\Signere\DocumentConvert;

class DocumentConvertTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/DocumentConvert';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_convert_a_document()
    {
        $detail = '';

        $url = $this->uri;

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, []])->andReturn([]);

        $dc = new DocumentConvert($this->makeClient($detail), $this->headers);
        $response = $dc->convert([]);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }
}
