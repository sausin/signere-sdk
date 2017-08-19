<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Carbon\Carbon;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Sausin\Signere\Document;
use Illuminate\Support\Facades\Config;

class DocumentTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/Document';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_the_url_for_signing()
    {
        $detail = '';

        $url = sprintf(
            '%s/SignUrl?documentId=%s&signeeRefId=%s',
            $this->uri,
            $docId = str_random(36), $signeeRefId = str_random(36)
        );

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $dc = new Document($this->makeClient($detail), $this->headers);
        $response = $dc->getSignUrl($docId, $signeeRefId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_a_document()
    {
        $detail = '';

        $url = sprintf('%s/%s', $this->uri, $docId = str_random(36));

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $dc = new Document($this->makeClient($detail), $this->headers);
        $response = $dc->get($docId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_a_temporary_url_for_signed_doc()
    {
        $detail = '';

        $url = sprintf('%s/SignedDocument/TemporaryViewerUrl/%s', $this->uri, $docId = str_random(36));

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $dc = new Document($this->makeClient($detail), $this->headers);
        $response = $dc->getTemporaryUrl($docId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_a_list_of_documents()
    {
        $detail = '';

        $url = sprintf(
            '%s/?Status=%s&Fromdate=%s&JobId=%s&CreatedAfter=%s&ExternalCustomerRef=%s',
            $this->uri,
            $status = 'All',
            $from = '2016-07-29T14:23:23',
            $jobId = str_random(36),
            $after = '2016-07-26T14:23:23',
            $extRef = str_random(10)
        );

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $dc = new Document($this->makeClient($detail), $this->headers);
        $response = $dc->getList(
            $jobId,
            [
                'status' => $status,
                'from_date' => $from,
                'created_after' => $after,
                'ext_cust_ref' => $extRef
            ]
        );

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_a_document()
    {
        $detail = '';

        $url = $this->uri;

        $subItem = [
            'SigneeRefId' => '8210132929654b8eb02bd7e33250c069', 
            'FirstName' => 'Kari', 
            'LastName' => 'Normann', 
            'Email' => 'kari@normann.no'
        ];
        $body = [
            'Description' => str_random(150),
            'FileContent' => base64_encode(str_random(120)),
            'FileMD5CheckSum' => str_random(30),
            'FileName' => str_random(15),
            'Language' => str_random(2),
            'SigneeRefs' => [$subItem, $subItem],
            'SignJobId' => str_random(),
            'Title' => str_random()
        ];

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body, true])->andReturn([]);

        $dc = new Document($this->makeClient($detail), $this->headers);
        $response = $dc->create($body);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_cancel_a_document_signing()
    {
        $detail = '';

        $url = sprintf('%s/CancelDocument', $this->uri);

        $body = [
            'CanceledDate' => '2016-07-19T12:56:12',
            'DocumentID' => str_random(36),
            'Signature' => str_random(30)
        ];

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);

        $dc = new Document($this->makeClient($detail), $this->headers);
        $response = $dc->cancel($body);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_change_a_document_signing_deadline()
    {
        $detail = '';

        $url = sprintf('%s/ChangeDeadline', $this->uri);

        $body = [
            'DocumentID' => str_random(36),
            'NewDeadline' => '2016-07-19T12:56:12',
            'ProviderID' => str_random(36)
        ];

        $this->headers->shouldReceive('make')->once()->withArgs(['PUT', $url, $body])->andReturn([]);

        $dc = new Document($this->makeClient($detail), $this->headers);
        $response = $dc->changeDeadline($body);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }
}
