<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Sausin\Signere\DocumentProvider;

class DocumentProviderTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/DocumentProvider';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_provider_details()
    {
        $detail = '{"ProviderId": "1d4c883ed2ce48c8b4a9a08a00d4d3a4","Name": "The Bank","MvaNumber": "987654321","LegalContactName": "James Dean","LegalContactPhone": "+4722334455","LegalContactMobile": "+4799887766","LegalContactEmail": "legal@thebank.com","BillingAddress1": "Karl Johans gate 1","BillingAddress2": "Trondheimsveien 1","BillingPostalCode": "0020","BillingCity": "Oslo","BillingCountry": "Norway","LastLogin": "2012-10-10T17:10:03.0000000","FailedLoginCount": 0,"DealerId": "5515acea4896460c875fa08a00d4d3a9","BillingPlan": "Large","AllowedIPs": "192.168.1.1,192.168.1.2","CompanyPhone": "+4755225522","CompanyEmail": "post@company.com","CompanyUrl": "http://www.company.com","SMSSender": "UniPluss","EmailSenderAddress": "post@firma.no","HaveOwnBankIdCertificate": true,"PrePaid": true,"DemoProvider": true}';

        $providerId = str_random(10);
        $url = sprintf('%s/%s', $this->uri, $providerId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $dp = new DocumentProvider($this->makeClient($detail), $this->headers);
        $response = $dp->getProviderAccount($providerId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_certificate_expiry()
    {
        $detail = '{"Expires": "2013-01-01T00:00:00.0000000"}';

        $url = sprintf('%s/CertificateExpires', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $dp = new DocumentProvider($this->makeClient($detail), $this->headers);
        $response = $dp->getCertExpiry();

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_usage_for_prepaid_account()
    {
        $detail = '{"Limit": 10,"Used": 6,"Available": 4}';

        $providerId = str_random(10);
        $url = sprintf('%s/quota/prepaid?ProviderId=%s', $this->uri, $providerId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $dp = new DocumentProvider($this->makeClient($detail), $this->headers);
        $response = $dp->getUsage($providerId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_usage_for_demo_account()
    {
        $detail = '{"Limit": 10,"Used": 6,"Available": 4}';

        $providerId = str_random(10);
        $url = sprintf('%s/quota/demo?ProviderId=%s', $this->uri, $providerId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $dp = new DocumentProvider($this->makeClient($detail), $this->headers);
        $response = $dp->getUsage($providerId, true);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_a_document_provider()
    {
        $detail = '{"ProviderId": "1d4c883ed2ce48c8b4a9a08a00d4d3a4","PrimaryApiKey":"REg5Um12TlpBY3FzM3Y0QURDWExrZz09LHhRbnpuK1RXZzUxV1Y1R0lvWlpEdUJ2UXRFUmQ2VGpXZTEzTmhTeWVkQ0U9","SecondaryApiKey":bFFVUGF6RkFWaG9uTE83TDJtWVU3Zz09LGQ3UjZGRkt6SkpydUNmb2pTZ0krRlc3Um9ib2k2UG9ZSWFudlNmcTIrQTg9"}';

        $body = [
            'Name' => 'The Bank',
            'MvaNumber' => '987654321',
            'CompanyPhone' => '+4755225522',
            'CompanyEmail' => 'post@company.com',
            'LegalContactName' => 'James Dean',
            'LegalContactPhone' => '+4722334455',
            'LegalContactEmail' => 'legal@thebank.com',
            'BillingAddress1' => 'Karl Johans gate 1',
            'BillingPostalCode' => '0020',
            'BillingCity' => 'Oslo',
            'DealerId' => '5515acea4896460c875fa08a00d4d3a9',
            'BillingPlan' => 'Large',
        ];
        $url = $this->uri;

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);

        $dp = new DocumentProvider($this->makeClient($detail, 2), $this->headers);
        $response = $dp->create($body);

        $this->assertEquals($detail, $response->getBody()->getContents());

        // test that it works without billing plan
        unset($body['BillingPlan']);
        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);

        $response = $dp->create($body);
        $this->assertEquals($detail, $response->getBody()->getContents());

        // test that billing plan can only be in a set of values
        $body['BillingPlan'] = 'Haha';
        $this->expectException('UnexpectedValueException');
        $dp->create($body);
    }

    /** @test */
    public function it_can_update_a_document_provider()
    {
        $detail = '{"ProviderId": "1d4c883ed2ce48c8b4a9a08a00d4d3a4","Name": "The New Bank","MvaNumber": "987654321","CompanyPhone": "+4755225522","CompanyEmail": "post@company.com","CompanyUrl": "http://www.company.com","LegalContactName": "James Dean","LegalContactPhone": "+4722334455","LegalContactMobile": "+4799887766","LegalContactEmail": "newlegaladdress@thenewbank.com","BillingAddress1": "Karl Johans gate 1","BillingAddress2": "Trondheimsveien 1","BillingPostalCode": "0020","BillingCity": "Oslo","BillingCountry": "Norway","DealerId": "5515acea4896460c875fa08a00d4d3a9","DealerReference": "114104","BillingPlan": "Medium","SMSSender": "UniPluss","EmailSenderAddress": "post@firma.no"}';

        $body = ['Mobile' => '98000000', 'ProviderId' => '1D4C883ED2CE48C8B4A9A08A00D4D3A4'];
        $url = $this->uri;

        $this->headers->shouldReceive('make')->once()->withArgs(['PUT', $url, $body])->andReturn([]);

        $dp = new DocumentProvider($this->makeClient($detail), $this->headers);
        $response = $dp->update($body);

        $this->assertEquals($detail, $response->getBody()->getContents());

        // test that it works without billing plan
        unset($body['Mobile']);

        $this->expectException('BadMethodCallException');
        $dp->update($body);
    }
}
