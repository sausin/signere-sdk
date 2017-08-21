<?php

namespace Sausin\Signere\Tests\Facades;

use Sausin\Signere\Facades\EventsFacade;
use Sausin\Signere\Tests\IntegrationTest;

class EventsFacadeTest extends IntegrationTest
{
    /** @test */
    public function it_substitutes_for_status()
    {
        EventsFacade::shouldReceive('getEncryptionKey')->once()->andReturn($str = str_random(10));

        $this->assertEquals($str, EventsFacade::getEncryptionKey());
    }
}
