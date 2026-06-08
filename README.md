# Capell Inertia Vue Adapter

The Vue adapter registers Vue 3 dependencies and the Capell Inertia Vue application entrypoint for Inertia-powered Capell pages and package routes.

## At A Glance

- Package: `capell-app/inertia-vue-adapter`
- Namespace: `Capell\InertiaVueAdapter\`
- Surfaces: public frontend
- Service provider: `Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider`
- Capell dependencies: `capell-app/core`, `capell-app/inertia`
- Framework dependencies: `@inertiajs/vue3`, `@vitejs/plugin-vue`, `vue`

## Why It Helps Your Capell Workflow

For teams, the adapter is the installable switch that lets a Capell Inertia theme use Vue components.

For developers, it registers the `vue` adapter key, NPM dependencies, build asset entrypoint, and component names through Capell's vendor asset system instead of requiring each app to hand-wire Vue bootstrapping.

## What It Adds

- Vue adapter metadata in `InertiaAdapterRegistry`.
- NPM dependency declarations for Vue and the Inertia Vue adapter.
- Build asset `vendor/capell/inertia-vue` with entrypoint `resources/js/app.js`.
- Default component map for `Capell/Page` and `Capell/Bookings/Request`.
- A vendor asset condition that defers to the bookings-specific Vue component pack when that package is installed.

## Boundaries

- This package owns generic Vue bootstrapping only.
- Feature-specific Vue components can live in theme/component packages such as `capell-app/theme-inertia-bookings-vue`.
- It owns no migrations, settings, routes, admin resources, or public Blade views.

## Docs

- [docs index](docs/README.md)
- [overview.md](docs/overview.md)
- [Capell Inertia](../inertia/README.md)
- [Theme Inertia Bookings Vue](../theme-inertia-bookings-vue/README.md)

## Testing

```bash
vendor/bin/pest packages/inertia-vue-adapter/tests --configuration=phpunit.xml
```
