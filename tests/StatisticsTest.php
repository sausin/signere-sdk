<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use Sausin\Signere\Statistics;
use PHPUnit\Framework\TestCase;

class StatisticsTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
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
        $data = '{"Signed":42,"Expired":42,"All":42,"PartialSigned":42,"CancledBySender":42,"CancledByRecipient":42}';
        $url1 = 'https://api.signere.no/api/Statistics?Year=&Month=&Day=&Status=All';
        $url2 = 'https://api.signere.no/api/Statistics?Year=2014&Month=&Day=&Status=All';
        $url3 = 'https://api.signere.no/api/Statistics?Year=&Month=12&Day=&Status=All';
        $url4 = 'https://api.signere.no/api/Statistics?Year=&Month=&Day=30&Status=All';

        // create a new status object
        $status = new Statistics($this->makeClient($data, 4), $this->headers);

        // test
        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url1])->andReturn([]);
        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url2])->andReturn([]);
        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url3])->andReturn([]);
        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url4])->andReturn([]);

        $response = $status->get();
        $this->assertEquals($data, $response->getBody()->getContents());

        $response = $status->get(2014);
        $this->assertEquals($data, $response->getBody()->getContents());

        $response = $status->get(null, 12);
        $this->assertEquals($data, $response->getBody()->getContents());

        $response = $status->get(null, null, 30);
        $this->assertEquals($data, $response->getBody()->getContents());
    }
}
