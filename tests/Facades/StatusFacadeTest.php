<?php

namespace Sausin\Signere\Tests\Facades;

use Sausin\Signere\Facades\StatusFacade;
use Sausin\Signere\Tests\IntegrationTest;

class StatusFacadeTest extends IntegrationTest
{
    /** @test */
    public function it_substitutes_for_status()
    {
        StatusFacade::shouldReceive('getServerTime')->once()->andReturn('2016-09-23T12:21:39');

        $this->assertEquals('2016-09-23T12:21:39', StatusFacade::getServerTime());
    }
}
