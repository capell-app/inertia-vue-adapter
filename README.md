# Capell Inertia Vue Adapter

<!-- prettier-ignore-start -->

## What It Does

The Vue adapter registers a Vue client runtime with the shared Capell Inertia bridge.

- **Price and availability:** Check current availability and price on the [Capell Marketplace listing](https://capell.app/extensions/inertia-vue-adapter).
- **Licence:** Check the licence terms on the [Capell Marketplace listing](https://capell.app/extensions/inertia-vue-adapter).
- **Requires:** `PHP ^8.4`, `capell-app/core ^1.0.66`, `capell-app/frontend ^1.0.2`, `capell-app/inertia ^1.0`, `spatie/laravel-package-tools ^1.14.1`.
- **Install:** `composer require capell-app/inertia-vue-adapter`.

It adds no admin screen. Vue-based Inertia themes can use the registered entrypoint and client runtime when they render public pages.

## Who It Is For

**For developers:** The provider supplies the adapter definition and assets expected by Capell Inertia, so themes do not need their own bridge wiring.

**For teams:** Teams can choose a Vue Inertia theme without changing how editors manage Capell content.

## Quick Start

1. Install the package: `composer require capell-app/inertia-vue-adapter`.
2. Verify the package provider and manifest contributions are registered in the host app.

## Configure

This extension works without configuration. There is nothing to set after install.

## Limits And Pitfalls

- Install the required Capell packages at the versions in `composer.json`: `capell-app/core ^1.0.66`, `capell-app/frontend ^1.0.2`, `capell-app/inertia ^1.0`.
- Keep public Blade and cached HTML free of authoring markers, model IDs, permissions, signed editor URLs, and lazy database queries.
- Custom write integrations must preserve invalidation for `inertia-vue` cache tags.

## Screens And Workflow

![Illustrative inertia vue adapter catalogue card](docs/screenshots/inertia-vue-adapter-catalogue-preview.png)

- Illustrative inertia vue adapter catalogue card (frontend).

## Install Impact

- Required packages: `capell-app/core`, `capell-app/frontend`, `capell-app/inertia`.
- Admin navigation: no admin page or resource contribution is declared.
- Admin/editor extensions: none declared.
- Permissions: no package permission declarations or Shield gates detected; host access rules still apply.
- Public routes: none declared.
- Database changes: no package migrations declared.
- Config: no package config files.
- Settings: no package settings declared.
- Queues or schedules: none declared.
- Cache tags: `inertia-vue`.
- Commands: none declared.

## Troubleshooting

| Symptom | Likely cause | Check | Fix |
| --- | --- | --- | --- |
| Package surface is missing after install | Provider or manifest is not loaded | Confirm `capell.json`, package `composer.json`, and provider registration | Reinstall the package, refresh Composer autoload, and clear host caches |
| Public output leaks unexpected state | Render data, cache variation, or authoring boundary has regressed | Check public Blade, cache tags, and public-output safety tests | Move data loading out of Blade and rerun the package public-output tests |

## Upgrading

No manual steps between minor versions.

## Next Steps

- [Package docs](docs/README.md)
- [Overview](docs/overview.md)
- [Troubleshooting](#troubleshooting)
- [Developer reference](docs/reference.md)
- [Marketplace assets](docs/assets/marketplace/)
- [Capell content language plan](../../docs/CONTENT_LANGUAGE_PLAN.md)
- [Capell documentation design system](../../docs/DESIGN_SYSTEM.md)
- [Capell and package ERD notes](../../docs/erd/capell-and-package-erds.md)
- Related packages: [Capell Inertia](../inertia/README.md).

<!-- prettier-ignore-end -->
