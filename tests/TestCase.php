<?php

namespace Astrotomic\AuthRecoveryCodes\Tests;

use Astrotomic\AuthRecoveryCodes\AuthRecoveryCodesServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            AuthRecoveryCodesServiceProvider::class,
        ];
    }
}
