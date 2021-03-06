<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use Sausin\Signere\RequestId;
use PHPUnit\Framework\TestCase;

class RequestIdTest extends TestCase
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
    public function it_can_get_session_with_auth_user_info()
    {
        // set the variables for use
        $details = '{"Name":"Stein","FirstName":"Stein","MiddleName":"Olav Hagen","LastName":"Davidsen","DateOfBirth":"071283","Status":"LOGIN","MetaData":[{"Key":"user.certificate.json","Value":""},{"Key":"user.certificate.json","Value":""}],"SocialSecurityNumber":"23071212345","IdentityProviderUniqueId":"9578-6000-4-48855","UserAgent":"WIN","IPAddress":"192.168.1.0","IdentityProvider":"NO_BANKID_WEB","ErrorCode":"C307","ErrorMessage":"PKI modul i SIM kort er blokkert."}';

        $guid = str_random(10);
        $metadata = 'true';
        $url = sprintf('https://api.signere.no/api/SignereId/%s?metadata=%s', $guid, $metadata);

        // create a new RequestId object
        $requestId = new RequestId($this->makeClient($details), $this->headers);

        // test
        $this->headers->shouldReceive('make')->withArgs(['GET', $url])->andReturn([]);

        $response = $requestId->getDetails($guid, $metadata === 'true' ? true : false);

        $this->assertEquals($details, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_check_if_session_completed()
    {
        // set the variables for use
        $details = '';

        $guid = str_random(10);
        $url = sprintf('https://api.signere.no/api/SignereId/Completed/%s', $guid);

        // create a new RequestId object
        $requestId = new RequestId($this->makeClient($details), $this->headers);

        // test
        $this->headers->shouldReceive('make')->withArgs(['GET', $url])->andReturn([]);

        $response = $requestId->check($guid);

        $this->assertEquals($details, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_a_signere_id_request()
    {
        // set the variables for use
        $details = '"Url":"https://id.signere.no/NoBankIDMobile/Start?sessionid=3HtyxmFpQ5zXQ5o7aBvTYpbq630jvpNlZe1TNwzSi81v2&providrId=c4ab63ae-81b6-49d2-b75c-a17301071188&iframe=False&webmessaging=False&language=NO&errorUrl=aHR0cHM6Ly9pZHRlc3Quc2lnbmVyZS5uby90ZXN0L2Vycm9yP3N0YXR1cz1bMF0%3D&","RequestId":3HtyxmFpQ5zXQ5o7aBvTYpbq630jvpNlZe1TNwzSi81v2","BankIDMobileReference":"Snill Bank","Status":"UNKNOWN"';

        $guid = str_random(10);
        $body = [
            'CancelUrl' => 'https://',
            'ErrorUrl' => 'https://',
            'ExternalReference' => str_random(10),
            'IdentityProvider' => 'NO_BANKID_WEB',
            'SuccessUrl' => 'https://',
        ];
        $url = 'https://api.signere.no/api/SignereId';

        // create a new RequestId object
        $requestId = new RequestId($this->makeClient($details), $this->headers);

        // test
        $this->headers->shouldReceive('make')->withArgs(['POST', $url, $body])->andReturn([]);

        $response = $requestId->create($body);

        $this->assertEquals($details, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_invalidate_a_signere_id_request()
    {
        // set the variables for use
        $details = '{"RequestId": "3HtyxmFpQ5zXQ5o7aBvTYpbq630jvpNlZe1TNwzSi81v2"}';

        $guid = str_random(10);
        $body = ['RequestId' => $guid];
        $url = 'https://api.signere.no/api/SignereId/Invalidate';

        // create a new RequestId object
        $requestId = new RequestId($this->makeClient($details), $this->headers);

        // test
        $this->headers->shouldReceive('make')->withArgs(['PUT', $url, $body])->andReturn([]);

        $response = $requestId->invalidate($guid);

        $this->assertEquals($details, $response->getBody()->getContents());
    }
}
