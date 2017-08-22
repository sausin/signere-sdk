<?php

namespace Sausin\Signere\Tests\Facades;

use Sausin\Signere\Facades\SignereEvents;
use Sausin\Signere\Tests\IntegrationTest;

class SignereEventsTest extends IntegrationTest
{
    /** @test */
    public function it_substitutes_for_status()
    {
        SignereEvents::shouldReceive('getEncryptionKey')->once()->andReturn($str = str_random(10));

        $this->assertEquals($str, SignereEvents::getEncryptionKey());
    }
}
