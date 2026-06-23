# Spec 017: SCSS Consolidation

## Objective

Clean up SCSS after Vue 2 migration: use modern `@use` syntax, remove dead dependencies, keep stylesheets minimal.

## Changes

### 1. Dead Dependency Removal

| Package | Reason |
|---------|--------|
| `chart.js` | Zero imports/usage in codebase |
| `moment` | Zero imports/usage (was peer dep of chart.js) |

### 2. `@import` → `@use` Migration

Dart Sass deprecated `@import`. Replace with `@use`:

**`resources/sass/_variables.scss`** — no change needed (already plain variables).

**`resources/sass/app.scss`:**
```scss
// Before
@import 'variables';
@import '~bootstrap/scss/bootstrap';

// After
@use 'variables' as *;
@use '~bootstrap/scss/bootstrap' as *;
```

`@use 'variables' as *` keeps the current behavior (variables available globally without namespace prefix).

### 3. Inline `library.scss`

Contains only 2 rules (4 lines). Inline into `app.scss` and delete the file.

Remove `resources/sass/library.scss` from Vite input in `vite.config.js`.

## Not in Scope

- **Slimming Bootstrap imports** — the full `bootstrap/scss/bootstrap` import is fine. Pruning individual components is possible but the gain is minimal vs. risk of missing something.
- **Removing Bootstrap SCSS dependency** — still in use for grid, cards, navbar, forms, buttons, alerts, etc.
- **Removing `@alpinejs/collapse`** — actively used for nav dropdowns.
- **Removing `@fortawesome/fontawesome-free`** — actively used for icons (31 matches).
- **Removing `axios`** — actively used for AJAX calls.
- **CSS reorganization/refactoring** — consolidation only.

## Acceptance Criteria

1. `chart.js` and `moment` removed from `package.json`
2. `@import` replaced with `@use` in all SCSS files (no deprecation warnings)
3. `library.scss` inlined into `app.scss`
4. `library.scss` removed from filesystem
5. `library.scss` removed from Vite input
6. `npm run build` succeeds
7. `npm run dev` (Vite dev) works without SCSS errors
