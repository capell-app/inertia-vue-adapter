<?php

declare(strict_types=1);

use Capell\Core\Data\VendorAssetData;
use Capell\Core\Enums\VendorAssetEnum;
use Capell\Core\Facades\CapellCore;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Capell\InertiaVueAdapter\Health\InertiaVueAdapterHealthCheck;
use Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider;
use Capell\InertiaVueAdapter\Tests\InertiaVueAdapterTestCase;

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

it('passes health when the vue adapter has been registered', function (): void {
    expect(InertiaVueAdapterHealthCheck::compatibleCapellApiVersion())->toBe('^4.0')
        ->and((new InertiaVueAdapterHealthCheck)->passes())->toBeTrue();
});
