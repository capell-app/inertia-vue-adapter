# Capell Inertia Vue Adapter

<!-- prettier-ignore-start -->

## What This Plugin Adds

Capell Inertia Vue Adapter is an **Available**, **No schema impact** Capell plugin in the **Capell Frontend** product group. It ships as `capell-app/inertia-vue-adapter` and extends these surfaces: frontend.

Vue 3 client adapter for Capell Inertia pages, package routes, and adapter-driven themes.

After install, Vue theme and package developers get registered Vue/Vite dependencies, a generic Vue entrypoint, and a bridge adapter key that Capell Inertia can resolve safely.

Status details:

- Status: Available
- Tier: core
- Bundle: frontend
- Composer package: `capell-app/inertia-vue-adapter`
- Namespace: `Capell\InertiaVueAdapter`
- Theme key: not applicable

## Why It Matters

**For developers:** The package gives developers package-owned service providers and a Vue client entrypoint instead of pushing adapter behaviour into core or application code.

**For teams:** Vue-based Capell frontends can share the same Inertia bridge while themes own the actual visual components.

## Screens And Workflow

No screenshot contract is required for this generic adapter. Visual screenshots belong to Vue theme/component packages that render actual public pages through it.

## Technical Shape

- Service providers: `Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider`.
- Health checks: `Capell\InertiaVueAdapter\Health\InertiaVueAdapterHealthCheck`.
- Cache tags: `inertia-vue`.

## Data Model

This package has no schema impact. It does not declare package-owned migrations or required tables.

It registers adapter metadata and vendor assets with `capell-app/inertia` and `capell-app/core`; it does not persist data.

## Install Impact

- Admin navigation: no admin surface declared.
- Permissions: none declared in `capell.json`.
- Public routes: none detected in package route files.
- Database changes: no package migrations declared.
- Settings: no package settings declared.
- Queues or schedules: none detected in standard package paths.
- Cache tags: `inertia-vue`.
- Commands: none declared.

## Common Pitfalls

- Keep public Blade and cached HTML free of authoring markers, model IDs, permissions, signed editor URLs, and lazy database queries.
- Set `CAPELL_INERTIA_ADAPTER=vue` when this generic Vue build should be used. A richer Vue theme package may suppress this generic build and provide its own component pack.
- Keep `composer.json`, `composer.local.json`, `capell.json`, docs, screenshots, and tests aligned when the package surface changes.

## Troubleshooting

| Symptom | Likely cause | Check | Fix |
| --- | --- | --- | --- |
| Package surface is missing after install | Provider or manifest is not loaded | Confirm `capell.json`, package `composer.json`, and provider registration | Reinstall the package, refresh Composer autoload, and clear host caches |
| Public output leaks unexpected state | Render data, cache variation, or authoring boundary has regressed | Check public Blade, cache tags, and public-output safety tests | Move data loading out of Blade and rerun the package public-output tests |

## Quick Start

1. Install the package: `composer require capell-app/inertia-vue-adapter`.
2. Set `CAPELL_INERTIA_ADAPTER=vue` in the host app.
3. Verify the Capell Inertia bridge reports the Vue adapter and that the Vue build entrypoint is included in vendor assets.

## Next Steps

- [Package docs index](README.md)
- [Improvement plan](improvement-plan.md)
- [Marketplace assets](assets/marketplace/)
- [Capell content language plan](../../../docs/CONTENT_LANGUAGE_PLAN.md)
- [Capell documentation design system](../../../docs/DESIGN_SYSTEM.md)
- [Capell and package ERD notes](../../../docs/erd/capell-and-package-erds.md)
- Related packages: [Inertia](../../inertia/README.md).
- Focused tests: `vendor/bin/pest packages/inertia-vue-adapter/tests --configuration=phpunit.xml`.

<!-- prettier-ignore-end -->
