# Spec 010: Alpine.js — LocationTypes Migration

**Status:** Implemented
**Applies to:** ADR-0002 (Alpine.js over Vue 2), ADR-0004 (Bootstrap SCSS kept until migration done), ADR-0016 (Blade partial + Alpine JS module pattern)

## Goal

Migrate the Vue 2 `LocationTypes` component to Alpine.js using the ADR-0016 pattern: one Blade partial for HTML, one Alpine JS module for logic, page templates stay clean.

## Files

| File | Action |
|---|---|
| `resources/views/library/locationtypes/_list.blade.php` | **Create** — Blade partial with Alpine attributes |
| `resources/views/library/locationtypes/index.blade.php` | Modify — replace `<all-location-types>` with `@include`, set `useVueRoot => false` |
| `resources/js/alpine/location-types.js` | **Create** — Alpine `data()` function |
| `resources/js/app.js` | Modify — import Alpine module, remove Vue registration |
| `resources/js/components/library/locationtypes/LocationTypes.vue` | Delete |

## Behavior

- Fetches location types from `locationtypes.all` on page load
- Shows table with ID, Name, delete button
- "Add" input + button — POST to `locationtypes.store`, prepend to table
- Delete button on each row — DELETE to `locationtypes.destroy`, remove row
- Empty state: "Add Location Types to start creating book editions!" message

## Implementation

### 1. Create Alpine module: `resources/js/alpine/location-types.js`

```js
export default function () {
    return {
        locationTypes: [],
        locationType: { name: '' },
        init() {
            axios.get(route('locationtypes.all')).then(response => {
                this.locationTypes = response.data.locationTypes;
            }).catch(error => {});
        },
        addLocationType() {
            axios.post(route('locationtypes.store'), {
                locationType: this.locationType,
            }).then(response => {
                this.locationTypes.push(response.data.added);
                this.locationType.name = '';
            }).catch(error => {});
        },
        removeLocationType(index) {
            axios.delete(route('locationtypes.destroy', { locationtype: this.locationTypes[index].id })).then(response => {
                this.locationTypes.splice(index, 1);
            }).catch(error => {});
        },
    };
}
```

### 2. Create Blade partial: `_list.blade.php`

```blade
<div x-data="locationTypes" class="col-12">
    <table class="table table-sm" x-show="locationTypes.length > 0">
        <thead>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col"></th>
        </thead>
        <template x-for="(locationType, index) in locationTypes">
            <tr>
                <td x-text="locationType.id"></td>
                <td x-text="locationType.name"></td>
                <td>
                    <button class="btn btn-danger btn-sm" x-on:click="removeLocationType(index)">
                        <i class="fas fa-minus"></i>
                    </button>
                </td>
            </tr>
        </template>
    </table>
    <p x-show="locationTypes.length === 0">
        Add <b>Location Types</b> to start creating book editions!
    </p>
    <div class="input-group">
        <input class="form-control" type="text" x-model="locationType.name">
        <div class="input-group-append">
            <button class="btn btn-primary" x-on:click="addLocationType">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>
    </div>
</div>
```

### 3. Update `index.blade.php`

```blade
@extends('layouts.library', ['useVueRoot' => false])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('library.locationtypes._list')
        </div>
    </div>
@endsection
```

### 4. Update `app.js`

Add import at top:
```js
import locationTypes from './alpine/location-types';
```

Register:
```js
Alpine.data('locationTypes', locationTypes);
```

Remove:
```js
Vue.component('all-location-types', () => import('./components/library/locationtypes/LocationTypes.vue'));
```

### 5. Delete Vue component

Delete `resources/js/components/library/locationtypes/LocationTypes.vue`.

## Acceptance Criteria

1. `npm run build` succeeds
2. `php vendor/bin/phpunit` passes
3. Location types page loads with table of existing types
4. Add new type: POST `locationtypes.store`, row appears in table
5. Delete type: row removed from table
6. Empty state message shows when no types exist
7. No console errors
8. `LocationTypes.vue` no longer in JS bundle or source

## Implementation Notes

### Alpine `x-for` requires `useVueRoot => false`

This component uses `<template x-for>` which conflicts with Vue 2's template compiler. The page sets `useVueRoot => false` to omit the `#vue-root` wrapper, preventing Vue from mounting.

### Resource Route Parameter Names

`locationtypes.destroy` uses `{ locationtype: id }` (singular camelCase), not `{ id: id }`.

## Edge Cases

- **No location types**: Table hidden, prompt message shown
- **Add type with empty name**: Server validation handles it
- **Delete network error**: Logged to console, row stays
- **Add network error**: Silently caught, no row added
