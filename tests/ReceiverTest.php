<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Headers;
use GuzzleHttp\HandlerStack;
use Sausin\Signere\Receiver;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Support\Facades\Config;

class ReceiverTest extends TestCase
{
    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_one_or_all_receivers()
    {
        // set the variables for use
        $r1 = '{"Id":"1d4c883ed2ce48c8b4a9a08a00d4d3a4","FirstName":"Kari","LastName":"Normann","Mobile":"+4799775533","Email":"kari@normann.no","OrgNo":"987654321","CompanyName":"Example Company","ExternalId":"1234","SocSecHash":"01018574894","Category":"Employee"}';
        $r2 = sprintf('[%s,%s]', $r1, $r1);

        $provider = str_random(10);
        $receiver = str_random(10);

        $url1 = sprintf('https://api.signere.no/api/Receiver/%s?ProviderId=%s', $receiver, $provider);
        $url2 = sprintf('https://api.signere.no/api/Receiver?ProviderId=%s', $provider);

        // setup a mock handler
        $mock = new MockHandler([
            new Response(200, [], $r1),
            new Response(200, [], $r2)
        ]);

        // setup the client
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // create a new Receiver object
        $rObject = new Receiver($client, $this->headers);

        // test
        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url1])->andReturn([]);
        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url2])->andReturn([]);

        $response = $rObject->get($provider, $receiver);
        $this->assertEquals($r1, $response->getBody()->getContents());

        $response = $rObject->get($provider);
        $this->assertEquals($r2, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_a_receiver()
    {
        // set the variables for use
        $details = '{"Id": "1d4c883ed2ce48c8b4a9a08a00d4d3a4","FirstName": "Kari","LastName": "Normann","Email": "kari@normann.no"}';

        $body = ['FirstName' => 'Kari', 'LastName' => 'Normann', 'Email' => 'kari@normann.no'];
        $url = 'https://api.signere.no/api/Receiver';

        // setup a mock handler
        $mock = new MockHandler([
            new Response(200, [], $details)
        ]);

        // setup the client
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // create a new Receiver object
        $rObject = new Receiver($client, $this->headers);

        // test
        $this->headers->shouldReceive('make')->withArgs(['POST', $url, $body])->andReturn([]);

        $response = $rObject->create($body);

        $this->assertEquals($details, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_create_many_receivers()
    {
        // set the variables for use
        $details = '{"Id": "1d4c883ed2ce48c8b4a9a08a00d4d3a4","FirstName": "Kari","LastName": "Normann","Email": "kari@normann.no"}';

        $body = ['FirstName' => 'Kari', 'LastName' => 'Normann', 'Email' => 'kari@normann.no'];
        $url = 'https://api.signere.no/api/Receiver';

        // setup a mock handler
        $mock = new MockHandler([
            new Response(200, [], $details),
            new Response(200, [], $details)
        ]);

        // setup the client
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // create a new Receiver object
        $rObject = new Receiver($client, $this->headers);

        // test
        $this->headers->shouldReceive('make')->twice()->withArgs(['POST', $url, $body])->andReturn([]);

        $responses = $rObject->createMany([$body, $body]);

        $this->assertEquals($details, $responses[0]->getBody()->getContents());
        $this->assertEquals($details, $responses[1]->getBody()->getContents());
    }

    /** @test */
    public function it_can_delete_a_receiver()
    {
        // set the variables for use
        $details = '';

        $provider = str_random(10);
        $receiver = str_random(10);
        $url = sprintf('https://api.signere.no/api/Receiver/%s/%s', $provider, $receiver);

        // setup a mock handler
        $mock = new MockHandler([
            new Response(200, [], $details)
        ]);

        // setup the client
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // create a new Receiver object
        $rObject = new Receiver($client, $this->headers);

        // test
        $this->headers->shouldReceive('make')->withArgs(['DELETE', $url])->andReturn([]);

        $response = $rObject->delete($provider, $receiver);

        $this->assertEquals($details, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_delete_many_receivers()
    {
        // set the variables for use
        $details = '';

        $provider = str_random(10);
        $receiver = str_random(10);
        $url = sprintf('https://api.signere.no/api/Receiver/%s/%s', $provider, $receiver);

        // setup a mock handler
        $mock = new MockHandler([
            new Response(200, [], $details),
            new Response(200, [], $details)
        ]);

        // setup the client
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // create a new Receiver object
        $rObject = new Receiver($client, $this->headers);

        // test
        $this->headers->shouldReceive('make')->twice()->withArgs(['DELETE', $url])->andReturn([]);

        $responses = $rObject->deleteMany($provider, [$receiver, $receiver]);

        $this->assertEquals($details, $responses[0]->getBody()->getContents());
        $this->assertEquals($details, $responses[1]->getBody()->getContents());
    }

    /** @test */
    public function it_can_delete_all_receivers()
    {
        // set the variables for use
        $details = '';

        $provider = str_random(10);
        $url = sprintf('https://api.signere.no/api/Receiver/%s', $provider);

        // setup a mock handler
        $mock = new MockHandler([
            new Response(200, [], $details)
        ]);

        // setup the client
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // create a new Receiver object
        $rObject = new Receiver($client, $this->headers);

        // test
        $this->headers->shouldReceive('make')->withArgs(['DELETE', $url])->andReturn([]);

        $response = $rObject->deleteAll($provider);

        $this->assertEquals($details, $response->getBody()->getContents());
    }
}
