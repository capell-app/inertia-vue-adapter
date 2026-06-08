# Capell Inertia Vue Adapter Overview

Status: **Available, runtime adapter** · Kind: **plugin** · Tier: **core** · Bundle: **frontend** · Contexts: **frontend** · Product group: **Capell Frontend**

The Vue adapter supplies the framework-specific layer for Capell Inertia: Vue dependencies, an application entrypoint, and adapter metadata.

## Non-Technical Overview

Install this package when an Inertia-powered Capell frontend should use Vue. It does not create a visible admin screen or database table; it tells Capell's asset/runtime pipeline which framework assets to build and load.

## Developer Deep Dive

The provider registers:

- adapter key `vue`;
- build path `vendor/capell/inertia-vue`;
- entrypoint `resources/js/app.js`;
- components `Capell/Page` and `Capell/Bookings/Request`;
- NPM dependencies for Vue, Vite Vue plugin, and `@inertiajs/vue3`.

The generic build asset loads only when the active runtime uses Inertia, `capell-inertia.adapter` is `vue`, and the bookings-specific Vue component pack is not installed.

## Verification

```bash
vendor/bin/pest packages/inertia-vue-adapter/tests --configuration=phpunit.xml
```
