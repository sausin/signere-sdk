<?php

namespace Sausin\Signere\Tests\Facades;

use Sausin\Signere\Facades\SignereHeaders;
use Sausin\Signere\Tests\IntegrationTest;

class SignereHeadersTest extends IntegrationTest
{
    /** @test */
    public function it_substitutes_for_headers()
    {
        $array = SignereHeaders::make('GET', 'https://');

        $this->assertArrayHasKey('API-ID', $array);
        $this->assertArrayHasKey('API-TOKEN', $array);
        $this->assertArrayHasKey('API-USINGSECONDARYTOKEN', $array);
        $this->assertArrayHasKey('API-TIMESTAMP', $array);
        $this->assertArrayHasKey('API-RETURNERRORHEADER', $array);
        $this->assertArrayHasKey('API-ALGORITHM', $array);
    }
}
