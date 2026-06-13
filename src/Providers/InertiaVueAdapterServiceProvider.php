<?php

declare(strict_types=1);

namespace Capell\InertiaVueAdapter\Providers;

use Capell\Core\Data\VendorAssetData;
use Capell\Core\Facades\CapellCore;
use Capell\Core\Support\Assets\VendorAssetConditionRegistry;
use Capell\Core\Support\Packages\AbstractPackageServiceProvider;
use Capell\Inertia\Data\InertiaAdapterData;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Override;
use Spatie\LaravelPackageTools\Package;

class InertiaVueAdapterServiceProvider extends AbstractPackageServiceProvider
{
    public const string ADAPTER_KEY = 'vue';

    public const string BUILD_PATH = 'vendor/capell/inertia-vue';

    public const string ENTRYPOINT = 'resources/js/app.js';

    public const string THEME_BOOKINGS_VUE_PACKAGE = 'capell-app/theme-inertia-bookings-vue';

    public static string $name = 'capell-inertia-vue-adapter';

    public static string $packageName = 'capell-app/inertia-vue-adapter';

    public function configurePackage(Package $package): void
    {
        $package->name(self::$name);
    }

    public function packageBooted(): void
    {
        if (! $this->isPackageInstalled()) {
            return;
        }

        $this->registerAdapter();
        $this->registerVendorAssetConditions();
        $this->registerVendorAssets();
    }

    #[Override]
    protected function isPackageInstalled(): bool
    {
        return CapellCore::isPackageInstalled(static::$packageName);
    }

    private function registerAdapter(): void
    {
        $configure = fn (InertiaAdapterRegistry $registry): InertiaAdapterRegistry => $registry->register($this->adapterData());

        $this->app->afterResolving(InertiaAdapterRegistry::class, $configure);

        if ($this->app->resolved(InertiaAdapterRegistry::class)) {
            $configure($this->app->make(InertiaAdapterRegistry::class));
        }
    }

    private function registerVendorAssets(): void
    {
        foreach ($this->adapterData()->npmDependencies as $package => $version) {
            CapellCore::registerVendorAsset(VendorAssetData::npmDependency($package, $version, self::$packageName));
        }

        CapellCore::registerVendorAsset(VendorAssetData::buildAsset(
            path: self::BUILD_PATH,
            file: self::ENTRYPOINT,
            packageName: self::$packageName,
            condition: 'capell-inertia-adapter-vue',
        ));
    }

    private function registerVendorAssetConditions(): void
    {
        resolve(VendorAssetConditionRegistry::class)->register(
            'capell-inertia-adapter-vue',
            fn (mixed $context): bool => ($context->runtime->usesInertia ?? false)
                && config('capell-inertia.adapter', self::ADAPTER_KEY) === self::ADAPTER_KEY
                && ! CapellCore::isPackageInstalled(self::THEME_BOOKINGS_VUE_PACKAGE),
        );
    }

    private function adapterData(): InertiaAdapterData
    {
        return new InertiaAdapterData(
            key: self::ADAPTER_KEY,
            packageName: self::$packageName,
            npmDependencies: [
                '@inertiajs/vue3' => '^3.0.0',
                '@vitejs/plugin-vue' => '^6.0.0',
                'vue' => '^3.5.0',
            ],
            buildPath: self::BUILD_PATH,
            entrypoint: self::ENTRYPOINT,
            components: [
                'page' => 'Capell/Page',
                'bookingRequest' => 'Capell/Bookings/Request',
            ],
        );
    }
}
