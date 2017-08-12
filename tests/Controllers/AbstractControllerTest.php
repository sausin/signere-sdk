<?php

namespace Sausin\Signere\Tests\Controllers;

use Sausin\Signere\Signere;
use Sausin\Signere\Tests\IntegrationTest;

abstract class AbstractControllerTest extends IntegrationTest
{
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('app.key', 'base64:UTyp33UhGolgzCK5CJmT+hNHcA+dJyp3+oINtX+VoPI=');

        Signere::auth(function () {
            return true;
        });
    }
}