# Spec 008: Alpine.js — ShelfManager Migration

**Status:** Draft
**Applies to:** ADR-0002 (Alpine.js over Vue 2), ADR-0004 (Bootstrap SCSS kept until migration done), ADR-0016 (Blade partial + Alpine JS module pattern)

## Goal

Migrate the Vue 2 `ShelfManager` component to Alpine.js using the ADR-0016 pattern: one Blade partial for HTML, one Alpine JS module for logic, page templates stay clean.

## Files

| File | Action |
|---|---|
| `resources/views/home.blade.php` | Modify — replace `<shelf-manager>` with `@include` |
| `resources/views/library/shelves/_shelf-manager.blade.php` | **Create** — Blade partial with Alpine attributes |
| `resources/js/alpine/shelf-manager.js` | **Create** — Alpine `data()` function |
| `resources/js/app.js` | Modify — import Alpine module, remove Vue registration |
| `resources/js/components/library/shelves/ShelfManager.vue` | Delete |

## Behavior

- Fetches shelves from `shelves.index` on page load via GET
- Displays "Your Shelves" heading
- Grid of shelf cards: name, archive icon, link to `shelves.show`
- "Add New Shelf" card linking to `shelves.create`
- Delete button (X badge) on each shelf — sends DELETE to `shelves.destroy`, removes card from DOM on success
- Error on delete: logged to console

## Implementation

### 1. Create Alpine module: `resources/js/alpine/shelf-manager.js`

```js
export default function () {
    return {
        shelves: [],
        init() {
            axios.get(route('shelves.index')).then(response => {
                this.shelves = response.data.shelves;
            }).catch(error => {});
        },
        deleteShelf(shelfID, index) {
            axios.delete(route('shelves.destroy', { shelf: shelfID })).then(response => {
                this.shelves.splice(index, 1);
            }).catch(error => {
                console.log(error);
            });
        },
    };
}
```

### 2. Create Blade partial: `_shelf-manager.blade.php`

```blade
<div x-data="shelfManager" class="container-fluid">
    <div class="row">
        <div class="col-12 text-center">
            <h2>Your Shelves</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-3 text-center">
            <a href="{{ route('shelves.create') }}">
                <div class="card">
                    <div class="card-body">
                        Add New Shelf<br/>
                        <h1>+</h1>
                    </div>
                </div>
            </a>
        </div>
        <template x-for="(shelf, index) in shelves">
            <div class="col-3">
                <div class="card">
                    <div class="p-1 text-right">
                        <a class="badge-danger badge-pill text-small text-light"
                                x-on:click="deleteShelf(shelf.id, index)">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <a class="card-link" x-bind:href="route('shelves.show', { shelf: shelf.id })">
                        <div class="card-body text-center">
                            <div x-text="shelf.name"></div>
                            <h1><i class="fas fa-archive"></i></h1>
                        </div>
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>
```

### 3. Update `home.blade.php`

Replace `<shelf-manager>` with `@include`:

```blade
@include('library.shelves._shelf-manager')
```

### 4. Update `app.js`

Add import at top:
```js
import shelfManager from './alpine/shelf-manager';
```

Register:
```js
Alpine.data('shelfManager', shelfManager);
```

Remove:
```js
Vue.component('shelf-manager', () => import('./components/library/shelves/ShelfManager.vue'));
```

### 5. Delete Vue component

Delete `resources/js/components/library/shelves/ShelfManager.vue`.

## Acceptance Criteria

1. `npm run build` succeeds
2. `php vendor/bin/phpunit` passes
3. Home page loads with shelves displayed
4. Shelf cards link to correct show page
5. Delete X removes shelf from DOM
6. No console errors
7. `ShelfManager.vue` no longer in JS bundle or source

## Implementation Notes

### `x-on:click` inside `#vue-root`

Alpine inside `#vue-root` must use `x-on:click` not `@click`. See Spec 006 Implementation Notes for details.

### `x-bind:` not `:` for attribute binding inside `#vue-root`

Vue 2 interprets `:attr` as `v-bind:attr` shorthand. Alpine content inside `#vue-root` must use the full `x-bind:attr` form:

```html
<!-- CORRECT inside #vue-root — Alpine-only, Vue ignores unknown x-* attributes -->
<a x-bind:href="route('shelves.show', { shelf: shelf.id })">

<!-- WRONG — Vue 2 interprets :href as v-bind:href, crashes on route() not in Vue scope -->
<a :href="route('shelves.show', { shelf: shelf.id })">
```

This applies to **all** attribute bindings (`:href`, `:class`, `:style`, etc.) inside `#vue-root`.

### No `:key` on `<template x-for>`

Vue 2 processes `<template>` elements specially. Do not use `:key="..."` on `<template x-for>` — Alpine handles keying internally. Vue 2 will choke on the expression.

### Resource Route Parameters

`shelves.show` uses `{ shelf: shelf.id }` (singular resource name), not `{ id: shelf.id }`.

## Edge Cases

- **No shelves**: Only the "Add New Shelf" card renders
- **Delete network error**: Logged to console, card stays in DOM
- **API failure on load**: Empty shelves list, no crash
