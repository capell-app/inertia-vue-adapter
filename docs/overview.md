# Capell Inertia Vue Adapter

<!-- prettier-ignore-start -->

## What it does

Capell Inertia Vue Adapter registers the Vue 3 client runtime, Vite application entrypoint, NPM dependencies, and `Capell/Page` component metadata for the shared Capell Inertia bridge.

## Do I need to do anything?

Editors usually do nothing. Integrators install this package alongside `capell-app/inertia`, set `CAPELL_INERTIA_ADAPTER=vue`, and include the generated Vite assets in the application's normal frontend build.

## Where it shows up

There is no package-owned admin screen or setting. Its application asset is contributed only when a public page uses the Inertia runtime and `vue` is the selected adapter.

## Good to know

- Install this only when the consuming Inertia theme or package uses Vue 3.
- It works alongside the base Capell Inertia bridge; the bridge owns the shared renderer and middleware.
- The adapter readiness check in Diagnostics confirms the registered package, Vite build path, and application entrypoint.
- Do not select this and the React adapter for the same active Inertia runtime; select the adapter that matches the consuming frontend.

---

For developers: see the [README](../README.md).

<!-- prettier-ignore-end -->
