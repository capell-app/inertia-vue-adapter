<?php

declare(strict_types=1);

namespace Capell\InertiaVueAdapter\Tests;

use Capell\Api\Providers\ApiServiceProvider;
use Capell\Core\Facades\CapellCore;
use Capell\Core\Providers\CapellServiceProvider;
use Capell\Core\Support\Packages\InstalledRuntimeLifecycle;
use Capell\Frontend\Providers\FrontendServiceProvider;
use Capell\Inertia\Providers\InertiaServiceProvider;
use Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider;
use Capell\LayoutBuilder\LayoutBuilderServiceProvider;
use Capell\Tests\Packages\PackagesTestCase;
use Illuminate\Foundation\Application;
use Override;

abstract class InertiaVueAdapterTestCase extends PackagesTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        CapellCore::forcePackageInstalled(InertiaServiceProvider::$packageName);
        CapellCore::forcePackageInstalled(InertiaVueAdapterServiceProvider::$packageName);
        CapellCore::forcePackageInstalled(FrontendServiceProvider::$packageName);
        CapellCore::forcePackageInstalled(ApiServiceProvider::$packageName);
        CapellCore::forcePackageInstalled(LayoutBuilderServiceProvider::$packageName);
        resolve(InstalledRuntimeLifecycle::class)->refreshForBootstrap(InertiaVueAdapterServiceProvider::$packageName);
    }

    /**
     * @param  Application  $app
     * @return class-string[]
     */
    #[Override]
    protected function getPackageProviders(mixed $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            CapellServiceProvider::class,
            FrontendServiceProvider::class,
            ApiServiceProvider::class,
            LayoutBuilderServiceProvider::class,
            InertiaServiceProvider::class,
            InertiaVueAdapterServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    #[Override]
    protected function getEnvironmentSetUp(mixed $app): void
    {
        parent::getEnvironmentSetUp($app);
    }
}
