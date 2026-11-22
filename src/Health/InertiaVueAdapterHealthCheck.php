<?php

declare(strict_types=1);

namespace Capell\InertiaVueAdapter\Health;

use Capell\Core\Contracts\Extensions\ChecksExtensionHealth;
use Capell\Core\Data\Diagnostics\DoctorCheckResultData;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider;
use Illuminate\Support\Collection;

final class InertiaVueAdapterHealthCheck implements ChecksExtensionHealth
{
    public static function compatibleCapellApiVersion(): string
    {
        return '^1.0';
    }

    /**
     * @return Collection<int, DoctorCheckResultData>
     */
    public static function runDiagnostics(): Collection
    {
        return (new self)->diagnostics();
    }

    public function passes(): bool
    {
        return self::runDiagnostics()->every(
            static fn (DoctorCheckResultData $result): bool => $result->passed,
        );
    }

    /**
     * @return Collection<int, DoctorCheckResultData>
     */
    private function diagnostics(): Collection
    {
        return collect([
            $this->registrationCheck(),
            $this->entrypointCheck(),
        ]);
    }

    private function registrationCheck(): DoctorCheckResultData
    {
        $adapter = app()->bound(InertiaAdapterRegistry::class)
            ? resolve(InertiaAdapterRegistry::class)->get(InertiaVueAdapterServiceProvider::ADAPTER_KEY)
            : null;

        $passed = $adapter !== null
            && $adapter->packageName === InertiaVueAdapterServiceProvider::$packageName
            && $adapter->buildPath === InertiaVueAdapterServiceProvider::BUILD_PATH
            && $adapter->entrypoint === InertiaVueAdapterServiceProvider::ENTRYPOINT
            && $adapter->components !== [];

        return new DoctorCheckResultData(
            label: 'Inertia Vue adapter: registered with the Inertia bridge',
            passed: $passed,
            message: $passed
                ? 'The Vue adapter is registered with its build path, entrypoint and page components.'
                : 'The Vue adapter is missing from the Inertia adapter registry or registered with different build details.',
            remediation: $passed
                ? null
                : 'Install capell-app/inertia and make sure the Vue adapter package is installed and booted.',
            id: 'inertia-vue-adapter.registered',
        );
    }

    private function entrypointCheck(): DoctorCheckResultData
    {
        $passed = is_file(dirname(__DIR__, 2) . '/' . InertiaVueAdapterServiceProvider::ENTRYPOINT);

        return new DoctorCheckResultData(
            label: 'Inertia Vue adapter: front-end entrypoint shipped',
            passed: $passed,
            message: $passed
                ? 'The Vue adapter entrypoint file is present in the package.'
                : 'The Vue adapter entrypoint file is missing from the package, so the front-end build cannot compile.',
            remediation: $passed
                ? null
                : 'Reinstall capell-app/inertia-vue-adapter so its resources/js directory is complete.',
            id: 'inertia-vue-adapter.entrypoint',
        );
    }
}
