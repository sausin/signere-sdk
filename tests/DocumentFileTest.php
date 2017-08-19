<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Carbon\Carbon;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Sausin\Signere\DocumentFile;

class DocumentFileTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/DocumentFile';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_a_signed_document()
    {
        $detail = 'dkfjskdljfslkdjfs';

        $documentId = str_random(10);
        $url = sprintf('%s/Signed/%s', $this->uri, $documentId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $df = new DocumentFile($this->makeClient($detail), $this->headers);
        $response = $df->getSigned($documentId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_a_unsigned_document()
    {
        $detail = 'dkfjskdljfslkdjfs';

        $documentId = str_random(10);
        $url = sprintf('%s/Unsigned/%s', $this->uri, $documentId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $df = new DocumentFile($this->makeClient($detail), $this->headers);
        $response = $df->getUnSigned($documentId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_a_signed_pdf()
    {
        $detail = 'dkfjskdljfslkdjfs';

        $documentId = str_random(10);
        $url = sprintf('%s/SignedPDF/%s', $this->uri, $documentId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $df = new DocumentFile($this->makeClient($detail), $this->headers);
        $response = $df->getSignedPdf($documentId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_a_temporary_url()
    {
        $detail = '';

        $date = Carbon::now()->setTimezone('UTC')->addHours(2);
        $body = [
            'DocumentId' => str_random(10),
            'DocumentFileType' => 'PDF',
            'Expires' => substr($date->toIso8601String(), 0, 19),
        ];
        $url = sprintf('%s/TempUrl', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);

        $df = new DocumentFile($this->makeClient($detail), $this->headers);
        $response = $df->temporaryUrl($body['DocumentId'], $body['DocumentFileType'], $date);

        $this->assertEquals($detail, $response->getBody()->getContents());

        // check for exception
        $body['DocumentFileType'] = 'XLS';
        $this->expectException('UnexpectedValueException');
        $df->temporaryUrl($body['DocumentId'], $body['DocumentFileType'], $date);
    }

    /** @test */
    public function it_can_create_a_temporary_url_without_some_params()
    {
        $detail = '';
        $url = sprintf('%s/TempUrl', $this->uri);

        $this->headers->shouldReceive('make')->once()->andReturn([]);

        $df = new DocumentFile($this->makeClient($detail), $this->headers);
        $response = $df->temporaryUrl(str_random(10));

        $this->assertEquals($detail, $response->getBody()->getContents());
    }
}
