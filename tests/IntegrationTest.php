<?php

namespace Sausin\Signere\Tests;

use Orchestra\Testbench\TestCase;

abstract class IntegrationTest extends TestCase
{
    /**
     * Setup the test case.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Tear down the test case.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Get the service providers for the package.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['Sausin\Signere\SignereServiceProvider'];
    }

    /**
     * Load package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'HeadersFacade' => 'Signere\Facades\HeadersFacade',
            'StatusFacade'  => 'Signere\Facades\StatusFacade',
            'EventsFacade'  => 'Signere\Facades\EventsFacade',
        ];
    }
}
