<?php

declare(strict_types=1);

namespace Capell\InertiaVueAdapter\Health;

use Capell\Core\Contracts\Extensions\ChecksExtensionHealth;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider;

final class InertiaVueAdapterHealthCheck implements ChecksExtensionHealth
{
    public static function compatibleCapellApiVersion(): string
    {
        return '^4.0';
    }

    public function passes(): bool
    {
        return app()->bound(InertiaAdapterRegistry::class)
            && resolve(InertiaAdapterRegistry::class)->get(InertiaVueAdapterServiceProvider::ADAPTER_KEY) !== null;
    }
}
