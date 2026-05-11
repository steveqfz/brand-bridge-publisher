<?php

declare(strict_types=1);

namespace BrandBridge\Tests;

use BrandBridge\BrandBridgeServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [BrandBridgeServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('brand-bridge.brand_key', 'vegastars');
        $app['config']->set('brand-bridge.publisher.enabled', true);
        $app['config']->set('brand-bridge.publisher.signing_key', 'test-secret-key');
    }
}
