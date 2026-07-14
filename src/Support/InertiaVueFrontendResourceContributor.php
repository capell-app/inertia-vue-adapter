<?php

declare(strict_types=1);

namespace Capell\InertiaVueAdapter\Support;

use Capell\Frontend\Contracts\FrontendResourceContributor;
use Capell\Frontend\Data\Assets\FrontendResourceContributionData;
use Capell\Frontend\Data\Assets\FrontendResourceData;
use Capell\Frontend\Data\Assets\ViteResourceSourceData;
use Capell\Frontend\Data\FrontendResourceContextData;
use Capell\Inertia\Actions\ResolveInertiaAdapterKeyAction;
use Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider;

final class InertiaVueFrontendResourceContributor implements FrontendResourceContributor
{
    public function resources(FrontendResourceContextData $context): array
    {
        if (! $context->runtime->usesInertia || ResolveInertiaAdapterKeyAction::run() !== InertiaVueAdapterServiceProvider::ADAPTER_KEY) {
            return [];
        }

        return [new FrontendResourceContributionData(FrontendResourceData::moduleScript(
            handle: 'capell-app/inertia-vue-adapter:application',
            package: InertiaVueAdapterServiceProvider::$packageName,
            source: new ViteResourceSourceData(InertiaVueAdapterServiceProvider::VITE_INPUT),
        ))];
    }
}
