# Capell Inertia Vue Adapter — Improvement & Growth Plan

> Package: capell-app/inertia-vue-adapter · Kind: plugin · Tier: core · Product group: Capell Frontend · Bundle: frontend · Status: Active

## 1. Snapshot

The Vue adapter registers the `vue` adapter with the shared Capell Inertia bridge, contributes Vue/Vite NPM dependencies, and exposes a generic Vue application entrypoint at `resources/js/app.js`. It deliberately owns no database tables, admin resources, settings, commands, or routes. When a richer Vue theme package such as `theme-inertia-bookings-vue` is installed, this generic adapter build condition turns off so the theme can provide the concrete component pack.

## Completed Improvement Slices

- **2026-06-14:** Routed the vendor asset condition through the shared `ResolveInertiaAdapterKeyAction`, so trimmed adapter config and invalid config behave consistently across public props and asset registration. Tightened health checks to verify the registered adapter belongs to this package and points at the expected build entrypoint. Added this package-local plan and refreshed generated docs placeholders.

## 2. Improvements

1. **Shipped 2026-06-14: align asset condition with sanitized adapter config.** The build condition now uses the bridge resolver instead of raw config, so `CAPELL_INERTIA_ADAPTER=" vue "` enables the Vue build and invalid config falls back away from it. — `src/Providers/InertiaVueAdapterServiceProvider.php`, `tests/Feature/InertiaVueAdapterServiceProviderTest.php` — S

2. **Shipped 2026-06-14: strengthen health readiness.** Health now verifies the registered adapter's package name, build path, and entrypoint, not only that a `vue` key exists. — `src/Health/InertiaVueAdapterHealthCheck.php`, `tests/Feature/InertiaVueAdapterServiceProviderTest.php` — S

3. **Add component-map drift tests.** The provider declares `Capell/Page` and `Capell/Bookings/Request`; `resources/js/app.js` imports matching files manually. Add a small file-existence or manifest test so a component map change cannot silently break the generic build. — `src/Providers/InertiaVueAdapterServiceProvider.php`, `resources/js/app.js` — S

4. **Surface adapter diagnostics through the bridge.** Once the Inertia bridge exposes adapter readiness details, report Vue package/version/build metadata there too. — `src/Health/InertiaVueAdapterHealthCheck.php`, `packages/inertia/src/Health/InertiaHealthCheck.php` — M

## 3. Missing Features

- **No SSR entrypoint.** The package only ships a browser entrypoint. SSR support should be explicit before claiming server-rendered Inertia pages.
- **No visual screenshot contract.** This generic adapter has no user-facing UI by itself. Screenshots should come from Vue theme/component packages that render actual pages through it.
- **No generated component map.** Components are registered manually in `app.js`; a generated map could remove drift once more page/component surfaces exist.

## 4. Risks

1. **Generic adapter suppression is package-name based.** The condition disables the generic build when `capell-app/theme-inertia-bookings-vue` is installed. If another full Vue theme becomes available, either register a broader suppressor contract or add explicit package keys.

2. **Vue version is a public build contract.** NPM dependency versions are registered as vendor assets. Keep adapter package tests aligned with any Vue/Vite major bump.

## 5. Positioning

Audience: frontend/package developers. Position this package as the included Vue client adapter for Capell Inertia, not as a theme. It gives developers a working Vue entrypoint and dependency contract; themes and component packs own the actual visual experience.

## 6. Roadmap

| Item                                           | Bucket | Effort | Impact | Ref |
| ---------------------------------------------- | ------ | ------ | ------ | --- |
| Align asset condition with sanitized config    | Done   | S      | Medium | §2  |
| Strengthen adapter health readiness            | Done   | S      | Medium | §2  |
| Add package-local improvement plan             | Done   | S      | Medium | §1  |
| Add component-map drift tests                  | Later  | S      | Medium | §2  |
| Add bridge-visible adapter diagnostics         | Later  | M      | Medium | §2  |
| Add explicit SSR entrypoint when SSR is needed | Later  | M      | Medium | §3  |
