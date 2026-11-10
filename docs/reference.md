# Capell Inertia Vue Adapter reference

<!-- prettier-ignore-start -->

Developer reference for `capell-app/inertia-vue-adapter`. Start with the [README](../README.md) for what the extension does and how to install it.

## Package Details

Capell Inertia Vue Adapter is an **Available**, **No schema impact** Capell plugin in the **Capell Frontend** product group. It ships as `capell-app/inertia-vue-adapter` and extends these surfaces: frontend.

- Status: Available
- Tier: core
- Bundle: frontend
- Composer package: `capell-app/inertia-vue-adapter`
- Namespace: `Capell\InertiaVueAdapter`
- Theme key: not applicable

## Technical Shape

### Service providers

- `Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider`

### Health checks

- `Capell\InertiaVueAdapter\Health\InertiaVueAdapterHealthCheck`

### Cache tags

- `inertia-vue`


## Data Model

This package has no schema impact. It registers runtime behaviour through `Capell\InertiaVueAdapter\Providers\InertiaVueAdapterServiceProvider` while persistence remains with Capell core or required packages.

<!-- prettier-ignore-end -->
