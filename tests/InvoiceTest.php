<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use Sausin\Signere\Invoice;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);

        $this->detail = '{"TimeStamp": "2012-12-12T15:00:00.0000000","Number": 2,"Description": "Document with ref: a12345 (external document id or title)","InvoiceType": "DOCUMENT_OFFLINE"}';

        $this->invoice = new Invoice($this->makeClient($this->detail), $this->headers);
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_invoices_for_a_specific_month()
    {
        $year = 2016;
        $month = 12;
        $url = sprintf('https://api.signere.no/api/Invoice/%s/%s', $year, $month);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);
        $response = $this->invoice->get($year, $month);

        $this->assertEquals($this->detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_does_not_accept_year_less_than_2015()
    {
        $year = 2014;
        $month = 12;
        $url = sprintf('https://api.signere.no/api/Invoice/%s/%s', $year, $month);

        $this->headers->shouldNotReceive('make');

        $this->expectException('UnexpectedValueException');
        $this->invoice->get($year, $month);
    }

    /** @test */
    public function it_only_accepts_valid_month()
    {
        $year = 2015;
        $month = 13;
        $url = sprintf('https://api.signere.no/api/Invoice/%s/%s', $year, $month);

        $this->headers->shouldNotReceive('make');

        $this->expectException('UnexpectedValueException');
        $this->invoice->get($year, $month);
    }
}
