<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Status;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Config\Repository;

class StatusTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        parent::setUp();

        $this->headers = m::mock(Headers::class);
        $this->config = m::mock(Repository::class);
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

        // create a new status object
        $status = new Status($this->makeClient($date), $this->headers, $this->config);

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
        $url = 'https://api.signere.no/api/Status/Ping/'.$pingRequest;

        // create a new status object
        $status = new Status($this->makeClient($pingRequest), $this->headers, $this->config);

        // test
        $this->config->shouldReceive('get')->once()->andReturn('');
        $this->headers->shouldReceive('make')->withArgs(['GET', $url])->andReturn([]);

        $response = $status->getServerStatus($pingRequest);

        $this->assertEquals($pingRequest, $response->getBody()->getContents());
    }
}
