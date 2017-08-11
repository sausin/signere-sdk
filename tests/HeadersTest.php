<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Config\Repository;

class HeadersTest extends TestCase
{
    public function setUp()
    {
        $this->config = m::mock(Repository::class);
        $this->headers = new Headers($this->config);
    }

    public function tearDown()
    {
        m::close();
    }
    
    /** @test */
    public function it_can_make_an_array_of_headers_for_get_requests()
    {
        $this->config->shouldReceive('get')->once()->with('signere.secondary_key')->andReturn('');
        $this->config->shouldReceive('get')->once()->with('signere.id')->andReturn('');

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
        $this->config->shouldReceive('get')->once()->with('signere.secondary_key')->andReturn('');
        $this->config->shouldReceive('get')->once()->with('signere.id')->andReturn('');

        $result = $this->headers->make('POST', 'https://example.com', ['sample' => 'data']);

        $this->assertTrue(isset($result['API-ID']));
        $this->assertTrue(isset($result['API-TIMESTAMP']));
        $this->assertTrue(isset($result['API-USINGSECONDARYTOKEN']));
        $this->assertTrue(isset($result['API-ALGORITHM']));
        $this->assertTrue(isset($result['API-RETURNERRORHEADER']));
        $this->assertTrue(isset($result['API-TOKEN']));

        // $this->expectException('BadMethodCallException');
        // $result = $this->headers->make('POST', 'https://example.com', []);
    }

    /** @test */
    public function it_can_make_an_array_of_headers_for_put_requests()
    {
        $this->config->shouldReceive('get')->once()->with('signere.secondary_key')->andReturn('');
        $this->config->shouldReceive('get')->once()->with('signere.id')->andReturn('');

        $result = $this->headers->make('PUT', 'https://example.com', ['sample' => 'data']);

        $this->assertTrue(isset($result['API-ID']));
        $this->assertTrue(isset($result['API-TIMESTAMP']));
        $this->assertTrue(isset($result['API-USINGSECONDARYTOKEN']));
        $this->assertTrue(isset($result['API-ALGORITHM']));
        $this->assertTrue(isset($result['API-RETURNERRORHEADER']));
        $this->assertTrue(isset($result['API-TOKEN']));

        // $this->expectException('BadMethodCallException');
        // $result = $this->headers->make('PUT', 'https://example.com', []);
    }

    /** @test */
    public function it_can_make_an_array_of_headers_for_delete_requests()
    {
        $this->config->shouldReceive('get')->once()->with('signere.secondary_key')->andReturn('');
        $this->config->shouldReceive('get')->once()->with('signere.id')->andReturn('');

        $result = $this->headers->make('DELETE', 'https://example.com');

        $this->assertTrue(isset($result['API-ID']));
        $this->assertTrue(isset($result['API-TIMESTAMP']));
        $this->assertTrue(isset($result['API-USINGSECONDARYTOKEN']));
        $this->assertTrue(isset($result['API-ALGORITHM']));
        $this->assertTrue(isset($result['API-RETURNERRORHEADER']));
        $this->assertTrue(isset($result['API-TOKEN']));
    }

    /** @test */
    public function it_can_require_primary_key_when_needed()
    {
        $this->config->shouldReceive('get')->once()->with('signere.primary_key')->andReturn('');
        $this->config->shouldReceive('get')->once()->with('signere.id')->andReturn('');

        $result = $this->headers->make('POST', 'https://example.com', ['some' => 'data'], true);

        $this->assertTrue(isset($result['API-ID']));
        $this->assertTrue(isset($result['API-TIMESTAMP']));
        $this->assertTrue(isset($result['API-USINGSECONDARYTOKEN']));
        $this->assertTrue(isset($result['API-ALGORITHM']));
        $this->assertTrue(isset($result['API-RETURNERRORHEADER']));
        $this->assertTrue(isset($result['API-TOKEN']));
    }
}
