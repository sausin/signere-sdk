<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Sausin\Signere\DocumentJob;
use Illuminate\Support\Facades\Config;

class DocumentJobTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/DocumentJob';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_document_job()
    {
        $detail = '{"Id": "25b884825ab44850ac39a08a00d4d3ae","ProviderId": "1d4c883ed2ce48c8b4a9a08a00d4d3a4","Contact_Name": "John Doe","Contact_Phone": "+4722334455","Contact_Mobile": "+4799887766","Contact_Email": "contact@thebank.com","Contact_Url": "www.thebank.com/contact"}';

        $jobId = str_random(10);
        $url = sprintf('%s/%s', $this->uri, $jobId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $dj = new DocumentJob($this->makeClient($detail), $this->headers);
        $response = $dj->get($jobId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_a_document_job()
    {
        $detail = '{"Id": "25b884825ab44850ac39a08a00d4d3ae"}';

        $body = ['Contact_Email' => 'ola@nordmann.no', 'Contact_Phone' => '+4722334455'];
        $url = $this->uri;

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);

        $dj = new DocumentJob($this->makeClient($detail), $this->headers);
        $response = $dj->create($body);

        $this->assertEquals($detail, $response->getBody()->getContents());

        unset($body['Contact_Email']);
        $this->expectException('BadMethodCallException');
        $response = $dj->create($body);
    }
}
