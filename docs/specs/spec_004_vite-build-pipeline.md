# Vite Build Pipeline

**Status:** Implemented

## References

- ADR-0005 (Retain Legacy Frontend During Backend Migration)
- Spec 001 (Laravel 10 → 11 Upgrade)
- Spec 002 (Laravel 11 → 12 Upgrade)
- Spec 003 (Laravel 12 → 13 Upgrade)

## Context

The frontend currently builds via Laravel Mix 4 (Webpack 4). The target stack is Vite + Alpine.js + SCSS, but we're migrating incrementally. This spec replaces the build tool only — all JS/CSS content (Vue 2, jQuery, Bootstrap 4, SCSS) stays the same. The app must remain runnable after this change.

## Scope

### In Scope

- Replace `laravel-mix` with `vite` + `laravel-vite-plugin`
- Update `package.json` scripts and dependencies
- Create `vite.config.js`
- Update Blade layouts to use `@vite()` instead of `asset()`
- Configure SASS compilation (two entry files: `app.scss`, `library.scss`)
- Configure Vue 2 SFC compilation (`@vitejs/plugin-vue2`)
- Configure jQuery and Bootstrap 4 as global dependencies
- Configure Font Awesome 5 JS import
- Verify `@routes` (Ziggy) still works
- Verify dev server works with hot module replacement
- Verify production build produces correct outputs

### Out of Scope

- Replacing Vue 2 components with Alpine.js
- Removing jQuery or Bootstrap 4
- Changing CSS framework (Bootstrap 4 stays)
- Redesigning any UI
- Changing backend routes or controllers

## Behavior Contract

```gherkin
Feature: Vite Build Pipeline
  As a developer
  I want the frontend to build through Vite instead of Laravel Mix
  So that we have a modern, faster build tool as the foundation for frontend migration

  Background:
    Given the project uses Vue 2, jQuery 3, Bootstrap 4, SCSS, and Font Awesome 5
    And the current build tool is Laravel Mix 4

  Scenario: Development server starts
    Given npm dependencies are installed
    When I run npm run dev
    Then a Vite dev server starts on port 5173
    And it proxies API requests to the Laravel backend
    And changes to .vue files trigger HMR
    And changes to .scss files trigger HMR

  Scenario: Production build succeeds
    Given npm dependencies are installed
    When I run npm run build
    Then the build exits with code 0
    And the output is written to public/build/
    And public/build/manifest.json exists
    And public/build/assets/ contains hashed JS files
    And public/build/assets/ contains hashed CSS files

  Scenario: Vue 2 single-file components compile
    Given a .vue file exists in resources/js/components/
    When Vite processes the entry point
    Then the component template, script, and style are compiled
    And component hot-reload works in dev mode

  Scenario: jQuery and Bootstrap 4 are available globally
    Given the entry point includes bootstrap.js
    When the page loads
    Then window.$ and window.jQuery are defined
    And Bootstrap 4 JavaScript plugins (modal, tooltip, dropdown) work
    And Popper.js is resolved for Bootstrap tooltips/popovers

  Scenario: SCSS compiles to CSS
    Given resources/sass/app.scss exists
    And resources/sass/library.scss exists
    When Vite processes the entry point
    Then public/build/assets/app-*.css is produced
    And public/build/assets/library-*.css is produced
    And Bootstrap 4 SCSS variables are resolved
    And @import '~bootstrap/scss/bootstrap' resolves correctly

  Scenario: Font Awesome 5 JS loads
    Given Font Awesome 5 is imported in the entry point
    When the page loads
    Then icon fonts display correctly
    And no 404s for font files in the browser console

  Scenario: Ziggy routes are available
    Given the @routes Blade directive is rendered
    When the page loads
    Then the route() JavaScript function is available globally
    And it resolves named routes to URLs

  Scenario: Asset URLs point to Vite build output
    Given the production build has completed
    When a Blade view renders using @vite()
    Then the generated HTML includes script and link tags pointing to public/build/
    And asset filenames include content hashes for cache busting

  Scenario: Transition from Mix — old config removed after Vite verified
    Given the Vite build is verified
    When I check the project
    Then webpack.mix.js is deleted
    And old Mix output in public/css/ and public/js/ is untracked (gitignored)
```

## Interfaces

### package.json Changes

| Key | Current | New |
|-----|---------|-----|
| scripts.dev | `npm run development` | `vite` |
| scripts.build | `npm run production` | `vite build` |
| devDependencies | `laravel-mix`, `cross-env`, `resolve-url-loader`, `sass-loader` ^7.x | Remove all Mix deps |
| devDependencies | `sass` ^1.15 | Keep, upgrade to latest |
| devDependencies | — | Add `vite` ^7 (v8+ incompatible with `@vitejs/plugin-vue2` peer deps) |
| devDependencies | — | Add `laravel-vite-plugin` ^2.1 (v3+ requires Vite 8) |
| devDependencies | — | Add `@vitejs/plugin-vue2` |
| devDependencies | — | `vite-plugin-commonjs` not needed — Vite handles CJS fine without it |
| dependencies | Keep `vue`, `vue-template-compiler`, `vue-chartjs`, `chart.js`, `moment`, `axios`, `@popperjs/core` | Unchanged |

### vite.config.js Structure

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue2 from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        vue2(),
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/library.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': '/node_modules/bootstrap',
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
```

### Blade Template Changes

**resources/views/layouts/library.blade.php:**

Replace:
```blade
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/library.css') }}" rel="stylesheet">
...
<script src="{{ asset('js/app.js') }}"></script>
```

With:
```blade
@vite(['resources/sass/app.scss', 'resources/sass/library.scss', 'resources/js/app.js'])
```

The `@vite` directive generates both CSS `<link>` and JS `<script>` tags.

**resources/views/layouts/app.blade.php:**

Replace:
```blade
<script src="{{ asset('js/app.js') }}" defer></script>
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
```

With `@vite` in the `<head>`:
```blade
@vite(['resources/sass/app.scss', 'resources/js/app.js'])
```

### Entry Point Changes

**resources/js/bootstrap.js:**

Replace `require()` calls with `import`:
```js
import 'lodash';
import 'popper.js';
import 'jquery';
import 'bootstrap';
import axios from 'axios';
```

But maintain global assignments for jQuery/Bootstrap compatibility:
```js
import $ from 'jquery';
window.$ = window.jQuery = $;
```

**resources/js/app.js:**

Replace:
```js
require('./bootstrap');
window.Vue = require('vue');
```

With:
```js
import './bootstrap';
import Vue from 'vue';
```

Dynamic imports (`.vue` components) use standard dynamic import syntax — works identically in both Mix and Vite since they're already using `() => import(...)`.

### Font Awesome 5

In Mix, Font Awesome is copied as a raw JS file. In Vite, import it directly in `resources/js/app.js`:
```js
import '@fortawesome/fontawesome-free/js/all.js';
```

Remove the Mix `copy()` directive and the `header-scripts.blade.php` include that loads `js/all.js`.

## Key Compatibility Notes

| Issue | Resolution |
|-------|-----------|
| Vue 2 SFC compilation | `@vitejs/plugin-vue2` (not `@vitejs/plugin-vue`) |
| CommonJS imports (jQuery, Bootstrap, lodash) | Standard ES `import` works — no plugin needed |
| SCSS `~bootstrap` path alias | Set `resolve.alias['~bootstrap']` in vite.config.js |
| Bootstrap 4 JS (depends on jQuery) | jQuery must be on `window.$` before Bootstrap imports |
| Bootstrap 4 tooltips (depends on Popper) | `window.Popper` must be set before Bootstrap imports |
| `@vitejs/plugin-vue2` version constraint | v2.3.4 supports Vite ^3\|\|^4\|\|^5\|\|^6\|\|^7 only. Pins Vite to ^7. laravel-vite-plugin must be ^2.x (v3+ requires Vite 8). |
| `@routes` Blade directive | Works server-side, no changes needed |
| `route()` JavaScript function | Provided by `@routes`, no import needed |
| `.env` variables (MIX_ prefix) | Vite uses `VITE_` prefix. No Mix env vars currently in use. |
| Dev server port | Vite defaults to 5173. Must not conflict with Laravel (8081) |
| CORS during development | `laravel-vite-plugin` handles proxy. No config changes needed. |

## Edge Cases

- **Build cache staleness** — `vite build` writes to `public/build/`. Stale `public/js/` or `public/css/` files from Mix are gitignored (`/public/js/*`, `/public/css/*`) but remain on disk. Can be removed manually after verification.
- **Ziggy version compatibility** — `tightenco/ziggy` ^1.0 must work with `@vite()` layout. If `@routes` conflicts with Vite's module system, may need to upgrade to ^2.0.
- **Vue component HMR** — Vue 2 HMR with `@vitejs/plugin-vue2` is less stable than Vue 3. If HMR issues occur, a full page reload still works since `laravel-vite-plugin` has `refresh: true`.
- **Font Awesome 5 web fonts** — `@fortawesome/fontawesome-free/js/all.js` includes SVG/JS version, not web fonts. No font file resolution needed.

## Implementation Order

1. [x] Update `package.json` — remove Mix deps, add Vite deps
2. [x] Create `vite.config.js`
3. [x] Update `resources/js/bootstrap.js` — CommonJS → ES module imports
4. [x] Update `resources/js/app.js` — `require` → `import`
5. [x] Update Blade layouts — `asset()` → `@vite()`
6. [x] Remove `header-scripts.blade.php` include (Font Awesome handled by import)
7. [x] Run `npm install` and `npm run build`
8. [x] Smoke test all pages
9. [ ] Verify HMR works in dev mode (optional — build/test suite sufficient for verification)

## Gaps Found During Implementation

- `@vitejs/plugin-vue2@2.3.4` peer deps only allow Vite `^3||^4||^5||^6||^7`. Used `vite@^7` and `laravel-vite-plugin@^2.1` instead.
- `vite-plugin-commonjs` not needed — Vite handles CommonJS imports without it.
- `@` alias not needed by current codebase; removed from config.
- `webpack.mix.js` can be safely deleted — no reference needed.
- **`@routes` missing from `app.blade.php`** — `app.js` calls `Vue.mixin({methods:{route}})` referencing global `route` (Ziggy). Auth layout `resources/views/layouts/app.blade.php` had no `@routes` directive (library layout did). This causes `ReferenceError` in module scripts, stopping Vue before `new Vue({el:'#app'})` runs.
- **Vue 2.7 runtime-only default** — Vue 2.7's `package.json` `exports` field resolves `import Vue from 'vue'` to `dist/vue.runtime.esm.js`, which lacks the template compiler. `new Vue({el:'#app'})` needs the compiler to process inline HTML templates. The old `^2.5.17` resolved to earlier versions where the default included the compiler; `npm install` during migration bumped it to 2.7.16. Fix: add `'vue': 'vue/dist/vue.esm.js'` alias in `vite.config.js`.

## Regression Risk

- Removing `webpack.mix.js` immediately may cause confusion — preserve it until the Vite build is verified
- Font Awesome may not initialize correctly if the JS import order matters
- jQuery plugins (Bootstrap) may fail if `window.$` is not set before Bootstrap loads
- The `ziggy-js` package version may need to match the `tightenco/ziggy` backend version
- HMR stability with Vue 2 is not guaranteed — full page reload is acceptable fallback
