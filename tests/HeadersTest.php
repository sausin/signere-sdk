<?php

namespace Sausin\Signere\Tests;

use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Config;

class HeadersTest extends TestCase
{
    public function setUp()
    {
        $this->headers = new Headers;
    }
    
    /** @test */
    public function it_can_make_an_array_of_headers_for_get_requests()
    {
        Config::shouldReceive('get')->twice()->andReturn('');

        $result = $this->headers->make('GET', 'https://example.com');

        $this->assertTrue(isset($result['API-ID']));
        $this->assertTrue(isset($result['API-TIMESTAMP']));
        $this->assertTrue(isset($result['API-USINGSECONDARYTOKEN']));
        $this->assertTrue(isset($result['API-ALGORITHM']));
        $this->assertTrue(isset($result['API-RETURNERRORHEADER']));
        $this->assertTrue(isset($result['API-TOKEN']));
    }

    /** @test */
    public function it_can_make_an_array_of_headers_for_post_requests()
    {
        Config::shouldReceive('get')->twice()->andReturn('');

        $result = $this->headers->make('POST', 'https://example.com', ['sample' => 'data']);

        $this->assertTrue(isset($result['API-ID']));
        $this->assertTrue(isset($result['API-TIMESTAMP']));
        $this->assertTrue(isset($result['API-USINGSECONDARYTOKEN']));
        $this->assertTrue(isset($result['API-ALGORITHM']));
        $this->assertTrue(isset($result['API-RETURNERRORHEADER']));
        $this->assertTrue(isset($result['API-TOKEN']));

        $this->setExpectedException('BadMethodCallException');
        $result = $this->headers->make('POST', 'https://example.com', []);
    }

    /** @test */
    public function it_can_make_an_array_of_headers_for_put_requests()
    {
        Config::shouldReceive('get')->twice()->andReturn('');

        $result = $this->headers->make('PUT', 'https://example.com', ['sample' => 'data']);

        $this->assertTrue(isset($result['API-ID']));
        $this->assertTrue(isset($result['API-TIMESTAMP']));
        $this->assertTrue(isset($result['API-USINGSECONDARYTOKEN']));
        $this->assertTrue(isset($result['API-ALGORITHM']));
        $this->assertTrue(isset($result['API-RETURNERRORHEADER']));
        $this->assertTrue(isset($result['API-TOKEN']));

        $this->setExpectedException('BadMethodCallException');
        $result = $this->headers->make('PUT', 'https://example.com', []);
    }

    /** @test */
    public function it_can_make_an_array_of_headers_for_delete_requests()
    {
        Config::shouldReceive('get')->twice()->andReturn('');

        $result = $this->headers->make('DELETE', 'https://example.com');

        $this->assertTrue(isset($result['API-ID']));
        $this->assertTrue(isset($result['API-TIMESTAMP']));
        $this->assertTrue(isset($result['API-USINGSECONDARYTOKEN']));
        $this->assertTrue(isset($result['API-ALGORITHM']));
        $this->assertTrue(isset($result['API-RETURNERRORHEADER']));
        $this->assertTrue(isset($result['API-TOKEN']));
    }
}
