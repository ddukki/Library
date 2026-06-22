# Working Doc — Library Revamp

## Status

Framework upgraded to Laravel 13.16.1, PHP 8.4, Docker. All custom middleware removed.
All Vue 2 components migrated to Blade + Alpine.js. Vue 2 removed from build chain entirely.

## Spec Queue

| # | Title | Status | Notes |
|---|---|---|---|
| 001 | Laravel 10→11 Upgrade | ✅ | |
| 002 | Laravel 11→12 Upgrade | ✅ | |
| 003 | Laravel 12→13 Upgrade | ✅ | |
| 004 | Vite Build Pipeline | ✅ | |
| 005 | Alpine + Bootstrap JS Removal | ✅ | |
| 006 | ShelfForm Alpine | ✅ | shelf-form.js, _form.blade.php, +tests |
| 007 | ShelfBooks Blade | ✅ | |
| 008 | ShelfManager Alpine | ✅ | |
| 009 | AuthorForm Alpine | ✅ | |
| 010 | LocationTypes Alpine | ✅ | |
| 011 | BookEditions Alpine | ✅ | book-editions.js, +tests |
| 012 | Pagination Alpine | ✅ | pagination.js composable |
| 013 | AuthorCard+BookCard Blade | ✅ | Partials with $item convention |
| 014 | AllAuthors+AllBooks Alpine | ✅ | Last page-level Vue migrations |
| 015 | BookForm+SelectAuthors Alpine | ✅ | book-form.js |
| 016 | Remove Vue 2 from Build | ✅ | Deps, plugin, #vue-root removed |

## Known Bugs / Gotchas

- Alpine `<select>` with `x-model="null"` doesn't auto-set to first option — init explicitly
- Controller `store()`/`update()` field names must match JS payload keys
- Container restart needed after PHP changes (PHP_CLI_SERVER_WORKERS=4 caches code)
- `@include` inside `<template x-for>` works via `$item` convention
- LSP errors for `Illuminate\*` are false positives (Docker-only PHP)

## Remaining Work

### SCSS Consolidation
Bootstrap 4 SCSS kept during migration per ADR-0004. All Vue components migrated — now can:
- Audit and remove unused Bootstrap variables/components
- Migrate Sass `@import` → `@use` (Dart Sass deprecation)
- Remove unused npm packages (`bootstrap` SCSS only, no JS)
- Evaluate `chart.js`/`moment` usage

### Infrastructure
- SQLite is a stopgap (ADR-0003) — evaluate server database post-migration
- CLI `swarm memory find` URL_INVALID bug (deferred)

### Upstream
- PR #201 awaiting maintainer vouch on issue #202

## Test Status

`php vendor/bin/phpunit` — **8 tests, 14 assertions, all pass**
- `EditionStoreTest` (2) — store success + auth guard
- `EditionShelveTest` (2) — shelve + unshelve
- `ShelfStoreTest` (3) — auth guard, data store, unique name
- `ExampleTest` (1) — basic assert
