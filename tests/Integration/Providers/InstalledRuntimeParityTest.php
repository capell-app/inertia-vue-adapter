<?php

declare(strict_types=1);

use Capell\Core\Actions\InstallPackageAction;
use Capell\Core\Actions\RuntimeRefresh\RefreshInstalledPackageRuntimeAction;
use Capell\Core\Facades\CapellCore;
use Capell\Frontend\Data\Assets\FrontendPackageDependencyData;
use Capell\Frontend\Enums\FrontendPackageDependencyType;
use Capell\Frontend\Support\Assets\FrontendPackageDependencyRegistry;
use Capell\Frontend\Support\Assets\FrontendViteInputRegistry;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider;
use Capell\Tests\Support\InstalledRuntimeParity;
use Capell\Tests\Support\InstalledRuntimeState;
use Capell\Tests\Support\InstalledRuntimeTestCase;

it('matches fresh installed boot after in-process installation and repeated refresh', function (): void {
    InstalledRuntimeParity::assertPackage('inertia-vue-adapter');
});

it('contributes once to registries resolved before installation and to later resolutions', function (bool $transient): void {
    InstalledRuntimeState::run(static function () use ($transient): void {
        $app = InstalledRuntimeTestCase::boot(InstalledRuntimeParity::manifest('inertia-vue-adapter'), false, sourceRoot: dirname(__DIR__, 3));
        $resolutions = new class
        {
            /** @var list<FrontendPackageDependencyRegistry> */
            private array $registries = [];

            public function record(FrontendPackageDependencyRegistry $registry): void
            {
                $this->registries[] = $registry;
            }

            public function clear(): void
            {
                $this->registries = [];
            }

            /** @return list<FrontendPackageDependencyRegistry> */
            public function all(): array
            {
                return $this->registries;
            }
        };
        if ($transient) {
            $app->bind(FrontendPackageDependencyRegistry::class, static function () use ($resolutions): FrontendPackageDependencyRegistry {
                $registry = new FrontendPackageDependencyRegistry;
                $resolutions->record($registry);

                return $registry;
            });
        }

        $dependencies = $app->make(FrontendPackageDependencyRegistry::class);
        $adapters = $app->make(InertiaAdapterRegistry::class);
        $inputs = $app->make(FrontendViteInputRegistry::class);
        $ownedDependencies = static fn (FrontendPackageDependencyRegistry $registry): array => array_values(array_map(
            static fn (FrontendPackageDependencyData $dependency): string => $dependency->name,
            array_filter($registry->all(), static fn (FrontendPackageDependencyData $dependency): bool => $dependency->package === InertiaVueAdapterServiceProvider::$packageName),
        ));

        expect($ownedDependencies($dependencies))->toBe([])
            ->and($adapters->get(InertiaVueAdapterServiceProvider::ADAPTER_KEY))->toBeNull()
            ->and($inputs->all())->not->toContain(InertiaVueAdapterServiceProvider::VITE_INPUT);

        $resolutions->clear();
        InstalledRuntimeTestCase::prepareLateInstallMigrations($app);
        $package = CapellCore::getPackage(InertiaVueAdapterServiceProvider::$packageName);
        InstallPackageAction::run($package);

        foreach ([1, 2, 3] as $phase) {
            expect($ownedDependencies($app->make(FrontendPackageDependencyRegistry::class)))->toBe(['@inertiajs/vue3', '@vitejs/plugin-vue', 'vue'])
                ->and($adapters->get(InertiaVueAdapterServiceProvider::ADAPTER_KEY)?->packageName)->toBe(InertiaVueAdapterServiceProvider::$packageName)
                ->and(array_values(array_filter($inputs->all(), static fn (string $input): bool => $input === InertiaVueAdapterServiceProvider::VITE_INPUT)))->toBe([InertiaVueAdapterServiceProvider::VITE_INPUT]);

            if (! $transient) {
                expect($ownedDependencies($dependencies))->toBe(['@inertiajs/vue3', '@vitejs/plugin-vue', 'vue']);
            }

            foreach ($resolutions->all() as $registry) {
                expect($ownedDependencies($registry))->toBe(['@inertiajs/vue3', '@vitejs/plugin-vue', 'vue']);
            }

            RefreshInstalledPackageRuntimeAction::run($package);
        }
    });
})->with(['singleton' => false, 'transient' => true]);

it('preserves differing dependency registrations through installation and refresh', function (string $constraint, FrontendPackageDependencyType $type): void {
    InstalledRuntimeState::run(static function () use ($constraint, $type): void {
        $app = InstalledRuntimeTestCase::boot(InstalledRuntimeParity::manifest('inertia-vue-adapter'), false, sourceRoot: dirname(__DIR__, 3));
        $dependencies = $app->make(FrontendPackageDependencyRegistry::class);
        $existing = new FrontendPackageDependencyData(
            name: 'vue',
            versionConstraint: $constraint,
            type: $type,
            package: InertiaVueAdapterServiceProvider::$packageName,
        );
        $dependencies->register($existing);

        InstalledRuntimeTestCase::prepareLateInstallMigrations($app);
        $package = CapellCore::getPackage(InertiaVueAdapterServiceProvider::$packageName);
        InstallPackageAction::run($package);

        foreach ([1, 2, 3] as $phase) {
            $requirements = array_values(array_filter(
                $dependencies->all(),
                static fn (FrontendPackageDependencyData $dependency): bool => $dependency->package === InertiaVueAdapterServiceProvider::$packageName && $dependency->name === 'vue',
            ));

            expect($requirements)->toHaveCount(2)
                ->and($requirements[0])->toBe($existing)
                ->and($requirements[1]->versionConstraint)->toBe('^3.5.0')
                ->and($requirements[1]->type)->toBe(FrontendPackageDependencyType::Runtime);

            RefreshInstalledPackageRuntimeAction::run($package);
        }
    });
})->with([
    'changed constraint' => ['^2.0.0', FrontendPackageDependencyType::Runtime],
    'changed type' => ['^3.5.0', FrontendPackageDependencyType::Development],
]);
