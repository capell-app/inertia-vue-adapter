<?php

declare(strict_types=1);

namespace Capell\InertiaVueAdapter\Providers;

use Capell\Core\Support\Packages\AbstractPackageServiceProvider;
use Capell\Frontend\Contracts\FrontendResourceContributor;
use Capell\Frontend\Data\Assets\FrontendPackageDependencyData;
use Capell\Frontend\Enums\FrontendPackageDependencyType;
use Capell\Frontend\Support\Assets\FrontendPackageDependencyRegistry;
use Capell\Frontend\Support\Assets\FrontendViteInputRegistry;
use Capell\Inertia\Data\InertiaAdapterData;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Capell\InertiaVueAdapter\Support\InertiaVueFrontendResourceContributor;
use Override;
use Spatie\LaravelPackageTools\Package;

final class InertiaVueAdapterServiceProvider extends AbstractPackageServiceProvider
{
    public const string ADAPTER_KEY = 'vue';

    public const string BUILD_PATH = 'vendor/capell/inertia-vue';

    public const string ENTRYPOINT = 'resources/js/app.js';

    public const string VITE_INPUT = 'vendor/capell-app/inertia-vue-adapter/resources/js/app.js';

    public static string $name = 'capell-inertia-vue-adapter';

    public static string $packageName = 'capell-app/inertia-vue-adapter';

    #[Override]
    public function configurePackage(Package $package): void
    {
        $package->name(self::$name);
    }

    #[Override]
    protected function bootInstalledRuntime(): void
    {
        $this->registerAdapter();
        $this->registerFrontendResources();
    }

    private function registerAdapter(): void
    {
        $configure = fn (InertiaAdapterRegistry $registry): InertiaAdapterRegistry => $registry->register($this->adapterData());

        $this->callAfterResolving(InertiaAdapterRegistry::class, $configure);
    }

    private function registerFrontendResources(): void
    {
        $registerDependencies = function (FrontendPackageDependencyRegistry $registry): void {
            foreach ($this->adapterData()->npmDependencies as $package => $version) {
                $type = str_starts_with($package, '@vitejs/') ? FrontendPackageDependencyType::Development : FrontendPackageDependencyType::Runtime;

                if (array_any($registry->all(), static fn (FrontendPackageDependencyData $dependency): bool => $dependency->package === self::$packageName && $dependency->name === $package && $dependency->versionConstraint === $version && $dependency->type === $type)) {
                    continue;
                }

                $registry->register(new FrontendPackageDependencyData(
                    name: $package,
                    versionConstraint: $version,
                    type: $type,
                    package: self::$packageName,
                ));
            }
        };

        $this->callAfterResolving(FrontendPackageDependencyRegistry::class, $registerDependencies);

        $registerInput = static function (FrontendViteInputRegistry $registry): void {
            $registry->register(self::VITE_INPUT, self::$packageName);
        };
        $this->callAfterResolving(FrontendViteInputRegistry::class, $registerInput);

        $this->app->tag(InertiaVueFrontendResourceContributor::class, FrontendResourceContributor::TAG);
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
