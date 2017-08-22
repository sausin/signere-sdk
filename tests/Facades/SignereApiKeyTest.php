<?php

namespace Sausin\Signere\Tests\Facades;

use SignereApiKey;
use Sausin\Signere\Tests\IntegrationTest;

class SignereApiKeyTest extends IntegrationTest
{
    /** @test */
    public function it_substitutes_for_status()
    {
        SignereApiKey::shouldReceive('createPrimary')->once()->andReturn('');

        $this->assertEquals('', SignereApiKey::createPrimary(str_random(10), 123456));
    }
}
