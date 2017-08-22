<?php

namespace Sausin\Signere\Tests\Facades;

use SignereStatus;
use Sausin\Signere\Tests\IntegrationTest;

class SignereStatusTest extends IntegrationTest
{
    /** @test */
    public function it_substitutes_for_status()
    {
        SignereStatus::shouldReceive('getServerTime')->once()->andReturn('2016-09-23T12:21:39');

        $this->assertEquals('2016-09-23T12:21:39', SignereStatus::getServerTime());
    }
}
