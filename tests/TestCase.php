<?php

namespace JeromeFitzpatrick\StartingPoint\Tests;

use JeromeFitzpatrick\StartingPoint\StartingPointServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [StartingPointServiceProvider::class];
    }
}
