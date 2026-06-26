# Spec 034: Bootstrap Removal

**Status:** Approved

**References:** ADR-0020, Spec 033 (all views replaced first), ADR-0020 Phase 4

## Objective

Remove Bootstrap as a dependency after all views have been migrated to the component library. This is the final cleanup — no Bootstrap classes remain in views, but the package and SCSS imports still exist.

## Prerequisites

All Spec 033 phases must be complete: **no Bootstrap CSS classes remain in any Blade view** (verified by regex audit).

## Scope

### In Scope

- Remove `bootstrap` from `package.json`
- Remove `bootstrap-scss` from `package.json` (if separate dependency)
- Remove Bootstrap SCSS imports from Vite entry files (`resources/css/app.scss`, `resources/css/library.scss`)
- Clean up any Bootstrap-related config (if any)
- Verify no broken styles

### Out of Scope

- Removing the SCSS component library (it replaces Bootstrap entirely)
- Removing or changing any view template HTML structure

## Procedure

### Step 1: Verify No Bootstrap Classes Remain

```bash
rg 'class="[^"]*(btn|card|form-control|badge|alert|nav|modal|dropdown|table|container|row|col-|mt-|mb-|p-|text-|float-|d-|justify-content-|align-items-)[^"]*"' resources/views/
```

Must return **zero results**. If any remain, Spec 033 is not complete.

### Step 2: Find Bootstrap SCSS Imports

```bash
rg 'bootstrap' resources/css/
rg 'import.*bootstrap' resources/
```

### Step 3: Remove Bootstrap from package.json

Edit `package.json`:

- Remove `"bootstrap"` from `dependencies` or `devDependencies`
- Remove `"bootstrap-scss"` if present

### Step 4: Remove Bootstrap Imports from SCSS

Remove lines like:

```scss
@import 'bootstrap';
@import '~bootstrap';
@import '~bootstrap/scss/bootstrap';
@import 'bootstrap/scss/bootstrap';
```

From all files in `resources/css/`.

### Step 5: Install Updated Dependencies

```bash
npm install
```

This removes Bootstrap from `node_modules` and updates `package-lock.json`.

### Step 6: Build and Verify

```bash
npm run build  # Vite production build
```

### Step 7: Run Test Suite

```bash
docker compose exec lib-dev php artisan test  # Pest
npx playwright test --config=tests/playwright.config.js  # Playwright
```

### Step 8: Final Bootstrap Artifact Check

```bash
rg '\.btn[^]' resources/views/ resources/css/ resources/js/
rg 'bootstrap' node_modules/.package-lock.json 2>/dev/null || echo "No bootstrap artifacts remain"
```

## Acceptance Criteria

1. `bootstrap` is not present in `package.json` dependencies
2. `bootstrap-scss` is not present in `package.json` dependencies
3. No `@import` or `@use` of Bootstrap files exists in `resources/css/`
4. Vite production build succeeds without errors
5. All 25 Pest tests pass
6. All 15 Playwright tests pass
7. No visual regression on any page (Bootstrap CSS no longer loaded)
8. `rg '\.btn[^-]' resources/views/ resources/css/` returns zero results
