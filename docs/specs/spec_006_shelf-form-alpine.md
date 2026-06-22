# Spec 006: Alpine.js — ShelfForm Migration

**Status:** Draft
**Applies to:** ADR-0002 (Alpine.js over Vue 2), ADR-0004 (Bootstrap SCSS kept until migration done), ADR-0016 (Blade partial + Alpine JS module pattern)

## Goal

Migrate the Vue 2 `ShelfForm` component to Alpine.js using the ADR-0016 pattern: one Blade partial for the HTML template, one Alpine JS module for the logic, page templates stay clean.

## Files

| File | Action |
|---|---|
| `resources/views/library/shelves/_form.blade.php` | **Create** — Blade partial with Alpine attributes |
| `resources/views/library/shelves/create.blade.php` | Modify — replace `<shelf-form>` with `@include` |
| `resources/views/library/shelves/edit.blade.php` | Modify — replace `<shelf-form>` with `@include` |
| `resources/js/alpine/shelf-form.js` | **Create** — Alpine `data()` function |
| `resources/js/app.js` | Modify — import Alpine module, remove Vue registration |
| `resources/js/components/library/shelves/ShelfForm.vue` | Delete |

## Behavior

- **Create mode** (`$shelf` is false): Empty input + "Add" button. POST to `shelves.store`, redirect to shelf show page.
- **Edit mode** (`$shelf` has id): Pre-filled input + "Update" button. PUT to `shelves.update`, redirect to shelf show page.
- **Error state**: Logged to console, no redirect.

## Implementation

### 1. Create Blade partial: `_form.blade.php`

```blade
<div x-data="shelfForm(@js($shelf))" class="col-12">
    <div class="form-group">
        <label for="shelf_name">Shelf Name</label>
        <input class="form-control"
               id="shelf_name"
               name="shelf_name"
               placeholder="Shelf Name"
               x-model="shelf.name">
    </div>
    <button class="btn btn-primary" @click="addShelf" x-show="!editShelf">
        + Add
    </button>
    <button class="btn btn-primary" @click="updateShelf" x-show="editShelf">
        + Update
    </button>
</div>
```

### 2. Update `create.blade.php`

```blade
@extends('layouts.library')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('library.shelves._form', ['shelf' => false])
    </div>
</div>
@endsection
```

### 3. Update `edit.blade.php`

```blade
@extends('layouts.library')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        @include('library.shelves._form', ['shelf' => $shelf])
    </div>
</div>
@endsection
```

### 4. Create Alpine module: `resources/js/alpine/shelf-form.js`

```js
export default function (editShelfData) {
    return {
        editShelf: editShelfData && editShelfData.id ? editShelfData : false,
        shelf: {
            name: editShelfData && editShelfData.id ? editShelfData.name : '',
            id: editShelfData && editShelfData.id ? editShelfData.id : null,
        },
        addShelf() {
            axios.post(route('shelves.store'), {
                shelf: this.shelf,
            }).then(response => {
                window.location.replace(route('shelves.show', { id: this.shelf.id }));
            }).catch(error => {
                console.error('Failed to create shelf:', error.response?.data || error);
            });
        },
        updateShelf() {
            axios.put(route('shelves.update', { id: this.shelf.id }), {
                shelf: this.shelf,
            }).then(response => {
                window.location.replace(route('shelves.show', { id: this.shelf.id }));
            }).catch(error => {
                console.error('Failed to update shelf:', error.response?.data || error);
            });
        },
    };
}
```

### 5. Update `app.js`

Add import at top:
```js
import shelfForm from './alpine/shelf-form';
```

Register before `Alpine.start()`:
```js
document.addEventListener('alpine:init', () => {
    Alpine.data('shelfForm', shelfForm);
});
```

Remove:
```js
Vue.component('shelf-form', () => import('./components/library/shelves/ShelfForm.vue'));
```

### 6. Delete Vue component

Delete `resources/js/components/library/shelves/ShelfForm.vue`.

## Acceptance Criteria

1. `npm run build` succeeds
2. `php vendor/bin/phpunit` passes
3. Create shelf: POST `shelves.store`, redirects to shelf show
4. Edit shelf: PUT `shelves.update`, redirects to shelf show
5. No console errors on either page
6. `ShelfForm.vue` no longer in JS bundle or source

## Edge Cases

- **Edit mode with empty/false shelf**: Falls through to create mode (Add button shown)
- **Network error on save**: Logged to console, no redirect
- **Empty name submitted**: Server validation handles it (no client-side validation — YAGNI)
