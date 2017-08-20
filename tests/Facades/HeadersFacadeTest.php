<?php

namespace Sausin\Signere\Tests\Facades;

use Sausin\Signere\Facades\HeadersFacade;
use Sausin\Signere\Tests\IntegrationTest;

class HeadersFacadeTest extends IntegrationTest
{
    /** @test */
    public function it_substitutes_for_headers()
    {
        $this->assertArrayHasKey('API-ID', HeadersFacade::make('GET', 'https://'));
        $this->assertArrayHasKey('API-TOKEN', HeadersFacade::make('GET', 'https://'));
        $this->assertArrayHasKey('API-USINGSECONDARYTOKEN', HeadersFacade::make('GET', 'https://'));
        $this->assertArrayHasKey('API-TIMESTAMP', HeadersFacade::make('GET', 'https://'));
        $this->assertArrayHasKey('API-RETURNERRORHEADER', HeadersFacade::make('GET', 'https://'));
        $this->assertArrayHasKey('API-ALGORITHM', HeadersFacade::make('GET', 'https://'));
    }
}
