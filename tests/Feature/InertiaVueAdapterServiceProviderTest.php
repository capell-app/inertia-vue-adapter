<?php

declare(strict_types=1);

use Capell\Frontend\Data\Assets\FrontendPackageDependencyData;
use Capell\Frontend\Data\Assets\ViteResourceSourceData;
use Capell\Frontend\Data\FrontendResourceContextData;
use Capell\Frontend\Data\FrontendRuntimeManifestData;
use Capell\Frontend\Enums\FrontendPackageDependencyType;
use Capell\Frontend\Enums\RenderingStrategyEnum;
use Capell\Frontend\Support\Assets\FrontendPackageDependencyRegistry;
use Capell\Frontend\Support\Assets\FrontendViteInputRegistry;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Capell\InertiaVueAdapter\Health\InertiaVueAdapterHealthCheck;
use Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider;
use Capell\InertiaVueAdapter\Support\InertiaVueFrontendResourceContributor;
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

it('registers typed dependencies and the conditional Vue Vite entry', function (): void {
    $dependencies = collect(resolve(FrontendPackageDependencyRegistry::class)->all())
        ->mapWithKeys(static fn (FrontendPackageDependencyData $dependency): array => [$dependency->name => [$dependency->versionConstraint, $dependency->type]])
        ->all();

    expect($dependencies)->toBe([
        '@tailwindcss/typography' => ['^0.5.9', FrontendPackageDependencyType::Runtime],
        '@tailwindcss/vite' => ['^4.0.13', FrontendPackageDependencyType::Runtime],
        'autoprefixer' => ['^10.4.13', FrontendPackageDependencyType::Runtime],
        'swiper' => ['^12.1.2', FrontendPackageDependencyType::Runtime],
        'fontaine' => ['^0.8.0', FrontendPackageDependencyType::Runtime],
        'laravel-vite-plugin' => ['^3.1.3', FrontendPackageDependencyType::Runtime],
        'npm-run-all' => ['^4.1.5', FrontendPackageDependencyType::Runtime],
        'tailwindcss' => ['^4.0.14', FrontendPackageDependencyType::Runtime],
        'vanilla-lazyload' => ['^19.1.3', FrontendPackageDependencyType::Runtime],
        'vite' => ['^8.0', FrontendPackageDependencyType::Runtime],
        '@inertiajs/vue3' => ['^3.0.0', FrontendPackageDependencyType::Runtime],
        '@vitejs/plugin-vue' => ['^6.0.0', FrontendPackageDependencyType::Development],
        'vue' => ['^3.5.0', FrontendPackageDependencyType::Runtime],
    ])->and(resolve(FrontendViteInputRegistry::class)->all())
        ->toContain(InertiaVueAdapterServiceProvider::VITE_INPUT);

    config()->set('capell-inertia.adapter', 'vue');
    $runtime = FrontendRuntimeManifestData::forRenderingStrategy(RenderingStrategyEnum::BladeOnly);
    $runtime->usesInertia = true;

    $resources = resolve(InertiaVueFrontendResourceContributor::class)->resources(new FrontendResourceContextData(null, null, null, null, null, $runtime));
    $source = $resources[0]->resource->source;

    throw_unless($source instanceof ViteResourceSourceData, RuntimeException::class, 'Vue adapter must contribute a Vite resource source.');

    expect($resources)->toHaveCount(1)
        ->and($source->entry)->toBe(InertiaVueAdapterServiceProvider::VITE_INPUT);
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

it('passes health when the vue adapter has been registered', function (): void {
    expect(InertiaVueAdapterHealthCheck::compatibleCapellApiVersion())->toBe('^1.0')
        ->and((new InertiaVueAdapterHealthCheck)->passes())->toBeTrue();
});
