<?php

namespace Sausin\Signere\Tests\Controllers;

use Mockery as m;
use Sausin\Signere\ApiKey;
use Illuminate\Support\Facades\Artisan;
use Sausin\Signere\Tests\IntegrationTest;

class RenewCommandTest extends IntegrationTest
{
    public function setUp()
    {
        parent::setUp();

        $this->apiKey = m::mock(ApiKey::class);
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    /** @test */
    public function it_should_renew_primary_key_if_nothing_is_specified()
    {
        $this->apiKey->shouldReceive('renewPrimary')->once()->andReturn('');
    
        $this->app->instance('Sausin\Signere\ApiKey', $this->apiKey);

        $resultCode = Artisan::call('signere:renew');
        $this->assertEquals(0, $resultCode);
    }

    /** @test */
    public function it_should_renew_primary_key_if_primary_is_specified()
    {
        $this->apiKey->shouldReceive('renewPrimary')->once()->andReturn('');
    
        $this->app->instance('Sausin\Signere\ApiKey', $this->apiKey);
        
        $resultCode = Artisan::call('signere:renew', ['--key' => 'primary']);
        $this->assertEquals(0, $resultCode);
    }

    /** @test */
    public function it_should_renew_secondary_key_if_secondary_is_specified()
    {
        $this->apiKey->shouldReceive('renewSecondary')->once()->andReturn('');
    
        $this->app->instance('Sausin\Signere\ApiKey', $this->apiKey);
        
        $resultCode = Artisan::call('signere:renew', ['--key' => 'secondary']);
        $this->assertEquals(0, $resultCode);
    }
}
