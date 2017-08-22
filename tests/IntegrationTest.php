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
            'SignereHeaders' => 'Sausin\Signere\Facades\SignereHeaders',
            'SignereApiKey' => 'Sausin\Signere\Facades\SignereApiKey',
            // 'SignereDocument' => 'Sausin\Signere\Facades\SignereDocument',
            // 'SignereDocumentConvert' => 'Sausin\Signere\Facades\SignereDocumentConvert',
            // 'SignereDocumentFile' => 'Sausin\Signere\Facades\SignereDocumentFile',
            // 'SignereDocumentJob' => 'Sausin\Signere\Facades\SignereDocumentJob',
            // 'SignereDocumentProvider' => 'Sausin\Signere\Facades\SignereDocumentProvider',
            // 'SignereEvents' => 'Sausin\Signere\Facades\SignereEvents',
            // 'SignereExternalLogin' => 'Sausin\Signere\Facades\SignereExternalLogin',
            // 'SignereExternalSign' => 'Sausin\Signere\Facades\SignereExternalSign',
            // 'SignereForm' => 'Sausin\Signere\Facades\SignereForm',
            // 'SignereInvoice' => 'Sausin\Signere\Facades\SignereInvoice',
            // 'SignereMessage' => 'Sausin\Signere\Facades\SignereMessage',
            // 'SignereReceiver' => 'Sausin\Signere\Facades\SignereReceiver',
            // 'SignereStatistics' => 'Sausin\Signere\Facades\SignereStatistics',
            'SignereStatus' => 'Sausin\Signere\Facades\SignereStatus',
        ];
    }
}
