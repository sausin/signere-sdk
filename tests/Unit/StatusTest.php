<?php

namespace Sausin\Signere\Tests\Unit;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Status;
use Sausin\Signere\Headers;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;

class StatusTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->headers = m::mock(Headers::class);
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function a_status_can_get_server_time()
    {
        // set the variables for use
        $date = '2017-06-13T21:20:13';
        $url = 'https://api.signere.no/api/Status/ServerTime';

        // setup a mock handler
        $mock = new MockHandler([
            new Response(200, [], $date)
        ]);

        // setup the client
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // create a new status object
        $status = new Status($client, $this->headers);

        // test
        $this->headers->shouldReceive('make')->withArgs(['GET', $url])->andReturn([]);

        $response = $status->getServerTime();        

        $this->assertEquals($date, $response->getBody()->getContents());
    }

    /** @test */
    public function a_status_can_check_if_service_is_working()
    {
        // set the variables for use
        $pingRequest = 'return string';
        $url = 'https://api.signere.no/api/Status/Ping/' . $pingRequest;

        // setup a mock handler
        $mock = new MockHandler([
            new Response(200, [], $pingRequest)
        ]);

        // setup the client
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // create a new status object
        $status = new Status($client, $this->headers);

        // test
        $this->headers->shouldReceive('make')->withArgs(['GET', $url])->andReturn([]);

        $response = $status->getServerStatus($pingRequest);        

        $this->assertEquals($pingRequest, $response->getBody()->getContents());
    }
}