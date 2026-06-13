# Capell Inertia Vue Adapter

<!-- prettier-ignore-start -->

## What This Plugin Adds

Capell Inertia Vue Adapter is an **Available**, **No schema impact** Capell plugin in the **Capell Frontend** product group. It ships as `capell-app/inertia-vue-adapter` and extends these surfaces: frontend.

Vue 3 asset adapter for Capell Inertia pages and package routes.

After install, the package affects public rendering, public routes, or frontend runtime behaviour.

Status details:

- Status: Available
- Tier: core
- Bundle: frontend
- Composer package: `capell-app/inertia-vue-adapter`
- Namespace: `Capell\InertiaVueAdapter`
- Theme key: not applicable

## Why It Matters

**For developers:** The package gives developers package-owned service providers instead of pushing this behaviour into core or application code.

**For teams:** Vue 3 adapter for Capell Inertia.

## Screens And Workflow

Docs gap: add `docs/screenshots.json` before promoting this package with visual workflow claims.

- Admin index screen if the package has a Filament resource.
- Create/edit screen if editors create records.
- Settings/configuration screen when settings exist.
- Frontend output when the package renders public pages.
- Package detail or install intent screen when marketplace-owned.

## Technical Shape

- Service providers: `Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider`.
- Health checks: `Capell\InertiaVueAdapter\Health\InertiaVueAdapterHealthCheck`.
- Cache tags: `inertia-vue`.

## Data Model

This package has no schema impact. It does not declare package-owned migrations or required tables.

Docs gap: document extension points here if the package delegates persistence to a host package.

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
- Keep `composer.json`, `composer.local.json`, `capell.json`, docs, screenshots, and tests aligned when the package surface changes.

## Troubleshooting

| Symptom | Likely cause | Check | Fix |
| --- | --- | --- | --- |
| Package surface is missing after install | Provider or manifest is not loaded | Confirm `capell.json`, package `composer.json`, and provider registration | Reinstall the package, refresh Composer autoload, and clear host caches |
| Public output leaks unexpected state | Render data, cache variation, or authoring boundary has regressed | Check public Blade, cache tags, and public-output safety tests | Move data loading out of Blade and rerun the package public-output tests |

## Quick Start

1. Install the package: `composer require capell-app/inertia-vue-adapter`.
2. Run the required setup: no package migrations are declared; clear cached config and routes if the host app uses caches.
3. Verify the package provider is registered and the related frontend, command, or extension point is active.

## Next Steps

- [Package docs](docs/README.md)
- [Overview](docs/overview.md)
- [Marketplace assets](docs/assets/marketplace/)
- [Capell content language plan](../../docs/CONTENT_LANGUAGE_PLAN.md)
- [Capell documentation design system](../../docs/DESIGN_SYSTEM.md)
- [Capell and package ERD notes](../../docs/erd/capell-and-package-erds.md)
- Related packages: [Inertia](../inertia/README.md).
- Focused tests: `vendor/bin/pest packages/inertia-vue-adapter/tests --configuration=phpunit.xml`.

<!-- prettier-ignore-end -->
