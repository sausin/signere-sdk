<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use Sausin\Signere\Message;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Config;

class MessageTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->detail = '{"Id":"113ae477c8054404927ea08a00d4d3af","DocumentID":"ea522127d971420595efa08a00d4d3ae","EmailTopic":"Updated information","EmailMessage":"You have received an update on the transaction status. Please check your profile on signere.no","RecipientEmailAddress":"customer1@hotmail.com","SMSText":"You have a new email from signere.no please check your email.","RecipientMobileNumber":"+4799887766","SentTime":"2013-01-01T00:00:00.0000000","MessageReceiverType":"SigneeRef","ReceiverName":"+4799887766","SenderSignature":"Ola Nordmann","Status":"SENDT"}';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_a_particular_message()
    {
        $messageId = str_random(10);
        $url = 'https://api.signere.no/api/Message/' . $messageId;

        $message = new Message($this->makeClient($this->detail), $this->headers);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);
        $response = $message->get($messageId);

        $this->assertEquals($this->detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_all_messages()
    {
        $detail = sprintf('[%s,%s]', $this->detail, $this->detail);

        $documentId = str_random(10);
        $url = 'https://api.signere.no/api/Message/Document/' . $documentId;

        $message = new Message($this->makeClient($detail), $this->headers);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);
        $response = $message->all($documentId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_send_a_message()
    {
        $detail = '';

        $body = [
            'DocumentID' => 'EA522127D971420595EFA08A00D4D3AE',
            'EmailMessage' => 'Hey hey hey!',
            'RecipientEmailAddress' => 'customer1@hotmail.com',
            'SenderSignature' => 'Ola Nordmann',
            'SigneeRef' => '459E946A2C154B3490A1A0B300FD3753'
        ];

        $url = 'https://api.signere.no/api/Message';

        $message = new Message($this->makeClient($detail), $this->headers);

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);
        $response = $message->sendMessage($body);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_send_a_new_document_message()
    {
        $detail = '';

        $body = [
            'DocumentID' => 'EA522127D971420595EFA08A00D4D3AE',
            'RecipientEmailAddress' => 'customer1@hotmail.com',
            'ReplaceEmail' => 'true',
            'SigneeRef' => '459E946A2C154B3490A1A0B300FD3753'
        ];

        $url = 'https://api.signere.no/api/Message/SendNewDocumentMessage';

        $message = new Message($this->makeClient($detail), $this->headers);

        $this->headers->shouldReceive('make')->once()->withArgs(['PUT', $url, $body])->andReturn([]);
        $response = $message->sendNewDocumentMessage($body);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_send_an_external_message()
    {
        $detail = '';

        $body = [
            'DocumentID' => 'EA522127D971420595EFA08A00D4D3AE',
            'EmailMessage' => 'Hey hey hey!',
            'EmailTopic' => 'My topic',
            'MobileNumber' => '12345678',
            'SenderSignature' => 'Ola Nordmann'
        ];

        $documentId = str_random(10);
        $url = 'https://api.signere.no/api/Message/SendExternalMessage';

        $message = new Message($this->makeClient($detail), $this->headers);

        $this->headers->shouldReceive('make')->once()->withArgs(['POST', $url, $body])->andReturn([]);
        $response = $message->sendExternalMessage($body);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }
}