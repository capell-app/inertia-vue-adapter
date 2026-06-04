<?php

declare(strict_types=1);

use Capell\InertiaVueAdapter\Tests\InertiaVueAdapterTestCase;

require_once __DIR__ . '/../../inertia/src/Data/InertiaAdapterData.php';
require_once __DIR__ . '/../../inertia/src/Support/InertiaAdapterRegistry.php';
require_once __DIR__ . '/../../inertia/src/Providers/InertiaServiceProvider.php';
require_once __DIR__ . '/../src/Health/InertiaVueAdapterHealthCheck.php';
require_once __DIR__ . '/../src/Providers/InertiaVueAdapterServiceProvider.php';
require_once __DIR__ . '/InertiaVueAdapterTestCase.php';

pest()->extend(InertiaVueAdapterTestCase::class)->group('inertia-vue-adapter')->in(__DIR__);
