# Working Doc — Library Revamp

## Status

Framework upgraded to Laravel 13.16.1, PHP 8.4, Docker. All custom middleware removed.
All Vue 2 components migrated to Blade + Alpine.js. Vue 2 removed from build chain entirely.
All 13 SCSS components + Blade components implemented and built (design tokens → primitives → components).
All 18 Style Guide documentation files written (`docs/style-guide/`).
All views migrated from Bootstrap to component library (Spec 033 complete).
Bootstrap fully removed from package.json and SCSS imports (Spec 034 complete).
All 25 Pest + 15 Playwright tests pass.

## Spec Queue

| # | Title | Status | Notes |
|---|---|---|---|
| 001 | Laravel 10→11 Upgrade | ✅ | |
| 002 | Laravel 11→12 Upgrade | ✅ | |
| 003 | Laravel 12→13 Upgrade | ✅ | |
| 004 | Vite Build Pipeline | ✅ | |
| 005 | Alpine + Bootstrap JS Removal | ✅ | |
| 006 | ShelfForm Alpine | ✅ | |
| 007 | ShelfBooks Blade | ✅ | |
| 008 | ShelfManager Alpine | ✅ | |
| 009 | AuthorForm Alpine | ✅ | |
| 010 | LocationTypes Alpine | ✅ | |
| 011 | BookEditions Alpine | ✅ | |
| 012 | Pagination Alpine | ✅ | |
| 013 | AuthorCard+BookCard Blade | ✅ | |
| 014 | AllAuthors+AllBooks Alpine | ✅ | |
| 015 | BookForm+SelectAuthors Alpine | ✅ | |
| 016 | Remove Vue 2 from Build | ✅ | |
| 017 | SCSS Consolidation | ✅ | |
| 018 | Design Tokens & Primitives | ✅ | Implemented |
| 019 | Alert Component | ✅ | Implemented |
| 020 | Badge Component | ✅ | Implemented |
| 021 | Button Component | ✅ | Implemented |
| 022 | Form Input Component | ✅ | Implemented |
| 023 | Card Component | ✅ | Implemented |
| 024 | List Component | ✅ | Implemented |
| 025 | Nav Component | ✅ | Implemented |
| 026 | Table Component | ✅ | Implemented |
| 027 | Dropdown Component | ✅ | Implemented |
| 028 | Modal Component | ✅ | Implemented |
| 029 | Tabs Component | ✅ | Implemented |
| 030 | Pagination Component | ✅ | Implemented |
| 031 | Avatar Component | ✅ | Implemented |
| 032 | Style Guide Documentation | ✅ | 18 files written |
| 033 | View Replacement | ✅ | All 6 phases complete |
| 034 | Bootstrap Removal | ✅ | Bootstrap removed from package.json and SCSS |

## Known Bugs / Gotchas

- Alpine `<select>` with `x-model="null"` doesn't auto-set to first option — init explicitly
- Controller `store()`/`update()` field names must match JS payload keys
- Container restart needed after PHP changes (PHP_CLI_SERVER_WORKERS=4 caches code)
- vendor/ on Docker named volume (`lib-vendor`) — re-copy on `composer install`
- `@include` inside `<template x-for>` works via `$item` convention
- LSP errors for `Illuminate\*` are false positives (Docker-only PHP)
- Ziggy view cache bug: `@routes` Blade directive compiled to old namespace — `php artisan view:clear`
- Modal requires `@alpinejs/focus` plugin (`x-trap.noscroll`)
- SCSS `@font-face`/Google Fonts `@import` must be after all `@use` rules (Dart Sass module system)
- Playwright `getByLabel('Password')` resolves 2 elements (Password + Confirm Password) — use `{ exact: true }`
- `php artisan view:clear` must run inside Docker container after Blade changes (compiled views are in container storage)

## Bootstrap Migration — View Replacement

### Phase 1: Auth Pages — ✅ Done
- `auth/login.blade.php` — `<x-card>`, `<x-form-input>`, `<x-button>`
- `auth/register.blade.php` — `<x-card>`, `<x-form-input>`, `<x-button>`
- `auth/verify.blade.php` — `<x-card>`, `<x-alert>`
- `auth/passwords/email.blade.php` — `<x-card>`, `<x-form-input>`, `<x-button>`, `<x-alert>`
- `auth/passwords/reset.blade.php` — `<x-card>`, `<x-form-input>`, `<x-button>`

### Phase 2: Layout Shell — ✅ Done
- `layouts/app.blade.php` — `<x-nav>`, `<x-dropdown>`, `<x-dropdown-link>`
- `layouts/nav.blade.php` — `<x-nav>`, `<x-dropdown>`, `<x-dropdown-link>`
- `layouts/library.blade.php` — spacing updated

### Phase 3: Static Pages — ✅ Done
- `home.blade.php` — `<x-card>`, `<x-button>`, input-group, flex utilities

### Phase 4: CRUD Lists — ✅ Done
- `library/locationtypes/index.blade.php` — updated
- `library/locationtypes/_list.blade.php` — `<table>`, `<x-button>`, input-group
- `library/books/index.blade.php` — `<x-button>`, input-group, flex
- `library/authors/index.blade.php` — `<x-button>`, input-group, flex
- `library/editions/index.blade.php` — cleaned
- `library/editions/_editions.blade.php` — CSS Grid layout, `<x-button>`, form-inputs
- `library/editions/_row.blade.php` — CSS Grid, `<x-button>`, btn-group, form-inputs
- `library/books/_book-card.blade.php` — `<x-card>`, flex
- `library/authors/_author-card.blade.php` — `<x-card>`, `<x-badge>`, flex
- `library/authors/_author-select-row.blade.php` — `<x-button>`, `<x-badge>`
- `library/authors/_selected-author-badge.blade.php` — `<x-badge>`
- `library/shelves/_shelf-manager.blade.php` — `<x-card>`, CSS Grid
- `library/partials/_pagination.blade.php` — pagination component classes

### Phase 5: CRUD Forms — ✅ Done
- `library/books/create.blade.php` — `<x-card>`, `<x-button>`, input-group
- `library/books/edit.blade.php` — `<x-card>`, `<x-button>`, input-group
- `library/authors/_form.blade.php` — `<x-card>`, `<x-button>`, form-row, form-input__field
- `library/authors/create.blade.php` — cleaned
- `library/authors/edit.blade.php` — cleaned
- `library/shelves/_form.blade.php` — `<x-button>`, form-input__field
- `library/shelves/create.blade.php` — cleaned
- `library/shelves/edit.blade.php` — cleaned

### Phase 6: Detail/Manager Views — ✅ Done
- `library/editions/show.blade.php` — `<x-card>`, border utility
- `library/editions/progress.blade.php` — progress-bar, form-input__field, `<x-button>`, `<table>`
- `library/editions/quotes.blade.php` — `<x-card>`, `<x-button>`, `<x-badge>`, form-input__field
- `library/editions/_shelve.blade.php` — custom-select, `<x-button>`, `<x-badge>`
- `library/books/show.blade.php` — `<x-card>`
- `library/shelves/show.blade.php` — cleaned
- `library/shelves/_books.blade.php` — `<x-card>`, CSS Grid
- `library/shelves/_edition-card.blade.php` — `<x-card>`

## Test Status

- **Pest:** 25 tests, all pass (Feature tests)
- **Playwright:** 15 tests, all pass (6 spec files, accessible locators)
- **SCSS Build:** `npm run build` succeeds (only Sass `darken()` deprecation warnings — our code, not Bootstrap)

## Infrastructure

- SQLite moved to Docker named volume `lib-db`
- vendor/ moved to Docker named volume `lib-vendor`
- PHP via `lib-dev` container (`php:8.4-cli`, `php artisan serve`)
- Node.js 22 on host for Vite/Playwright
- App at `localhost:8081`
