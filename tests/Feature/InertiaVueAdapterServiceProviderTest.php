<?php

declare(strict_types=1);

use Capell\Core\Data\VendorAssetData;
use Capell\Core\Enums\VendorAssetEnum;
use Capell\Core\Facades\CapellCore;
use Capell\Core\Support\Assets\VendorAssetConditionRegistry;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Capell\InertiaVueAdapter\Health\InertiaVueAdapterHealthCheck;
use Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider;
use Capell\InertiaVueAdapter\Tests\InertiaVueAdapterTestCase;
use Illuminate\Support\Facades\File;

require_once __DIR__ . '/../../../inertia/src/Data/InertiaAdapterData.php';
require_once __DIR__ . '/../../../inertia/src/Support/InertiaAdapterRegistry.php';
require_once __DIR__ . '/../../../inertia/src/Providers/InertiaServiceProvider.php';
require_once __DIR__ . '/../../src/Health/InertiaVueAdapterHealthCheck.php';
require_once __DIR__ . '/../../src/Providers/InertiaVueAdapterServiceProvider.php';
require_once __DIR__ . '/../InertiaVueAdapterTestCase.php';

uses(InertiaVueAdapterTestCase::class);

it('registers the vue adapter with the inertia bridge', function (): void {
    $adapter = resolve(InertiaAdapterRegistry::class)->get(InertiaVueAdapterServiceProvider::ADAPTER_KEY);

    throw_if($adapter === null, RuntimeException::class, 'Vue adapter was not registered.');

    expect($adapter)->not->toBeNull()
        ->and($adapter->key)->toBe('vue')
        ->and($adapter->packageName)->toBe(InertiaVueAdapterServiceProvider::$packageName)
        ->and($adapter->buildPath)->toBe(InertiaVueAdapterServiceProvider::BUILD_PATH)
        ->and($adapter->entrypoint)->toBe(InertiaVueAdapterServiceProvider::ENTRYPOINT)
        ->and($adapter->npmDependencies)->toMatchArray([
            '@inertiajs/vue3' => '^3.0.0',
            '@vitejs/plugin-vue' => '^6.0.0',
            'vue' => '^3.5.0',
        ])
        ->and($adapter->components)->toMatchArray([
            'bookingRequest' => 'Capell/Bookings/Request',
            'page' => 'Capell/Page',
        ]);
});

it('registers npm dependencies and the vue build entrypoint as vendor assets', function (): void {
    $npmDependencies = CapellCore::getVendorAssetsForType(VendorAssetEnum::NpmDependency)
        ->filter(static fn (VendorAssetData $asset): bool => $asset->packageName === InertiaVueAdapterServiceProvider::$packageName)
        ->mapWithKeys(static fn (VendorAssetData $asset): array => [$asset->dependencyName() => $asset->dependencyVersion()])
        ->all();

    $buildAssets = CapellCore::getVendorAssetsForType(VendorAssetEnum::BuildAsset)
        ->filter(static fn (VendorAssetData $asset): bool => $asset->packageName === InertiaVueAdapterServiceProvider::$packageName)
        ->map(static fn (VendorAssetData $asset): array => [
            'path' => $asset->path(),
            'file' => $asset->file(),
        ])
        ->values()
        ->all();

    expect($npmDependencies)->toBe([
        '@inertiajs/vue3' => '^3.0.0',
        '@vitejs/plugin-vue' => '^6.0.0',
        'vue' => '^3.5.0',
    ])->and($buildAssets)->toContain([
        'path' => InertiaVueAdapterServiceProvider::BUILD_PATH,
        'file' => InertiaVueAdapterServiceProvider::ENTRYPOINT,
    ]);
});

it('keeps declared vue components aligned with the generic app component map', function (): void {
    $adapter = resolve(InertiaAdapterRegistry::class)->get(InertiaVueAdapterServiceProvider::ADAPTER_KEY);
    throw_if($adapter === null, RuntimeException::class, 'Vue adapter was not registered.');

    $packagePath = dirname(__DIR__, 2);
    $entrypoint = $packagePath . '/' . InertiaVueAdapterServiceProvider::ENTRYPOINT;
    $appSource = File::get($entrypoint);

    foreach ($adapter->components as $componentName) {
        $componentPath = dirname($entrypoint) . '/Pages/' . $componentName . '.vue';

        expect(File::exists($componentPath))->toBeTrue()
            ->and($appSource)->toContain("'" . $componentName . "':");
    }
});

it('uses the shared adapter resolver for the vue build condition and disables it for the bookings vue component pack', function (): void {
    config()->set('capell-inertia.adapter', ' vue ');

    $context = (object) [
        'runtime' => (object) [
            'usesInertia' => true,
        ],
    ];

    $registry = resolve(VendorAssetConditionRegistry::class);

    expect($registry->passes('capell-inertia-adapter-vue', $context))->toBeTrue();

    config()->set('capell-inertia.adapter', ['invalid']);

    expect($registry->passes('capell-inertia-adapter-vue', $context))->toBeTrue();

    config()->set('capell-inertia.adapter', InertiaVueAdapterServiceProvider::ADAPTER_KEY);

    CapellCore::forcePackageInstalled(InertiaVueAdapterServiceProvider::THEME_BOOKINGS_VUE_PACKAGE);

    expect($registry->passes('capell-inertia-adapter-vue', $context))->toBeFalse();
});

it('passes health when the vue adapter has been registered', function (): void {
    expect(InertiaVueAdapterHealthCheck::compatibleCapellApiVersion())->toBe('^4.0')
        ->and((new InertiaVueAdapterHealthCheck)->passes())->toBeTrue();
});
