<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Events;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;

class EventsTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/events/encryptionkey';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_encryption_key()
    {
        $detail = 'sdl;fksdl;fksldfsdlkfsdl;kfs;ldk';

        $url = $this->uri;

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $event = new Events($this->makeClient($detail), $this->headers);
        $response = $event->getEncryptionKey();

        $this->assertEquals($detail, $response->getBody()->getContents());
    }
}
