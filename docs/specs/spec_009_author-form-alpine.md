# Spec 009: Alpine.js — AuthorForm Migration

**Status:** Implemented
**Applies to:** ADR-0002 (Alpine.js over Vue 2), ADR-0004 (Bootstrap SCSS kept until migration done), ADR-0016 (Blade partial + Alpine JS module pattern)

## Goal

Migrate the Vue 2 `AuthorForm` component to Alpine.js using the ADR-0016 pattern: one Blade partial for HTML, one Alpine JS module for logic, page templates stay clean.

## Files

| File | Action |
|---|---|
| `resources/views/library/authors/_form.blade.php` | **Create** — Blade partial with Alpine attributes |
| `resources/views/library/authors/create.blade.php` | Modify — replace `<author-form>` with `@include` |
| `resources/views/library/authors/edit.blade.php` | Modify — replace `<author-form>` with `@include` |
| `resources/js/alpine/author-form.js` | **Create** — Alpine `data()` function |
| `resources/js/app.js` | Modify — import Alpine module, remove Vue registration |
| `resources/js/components/library/authors/AuthorForm.vue` | Delete |

## Behavior

- **Create mode** (`$author` is false): Empty inputs + "Create Author" button. POST to `authors.store`, redirect to authors index.
- **Edit mode** (`$author` has id): Pre-filled inputs + "Update Author" button. PUT to `authors.update`, redirect to authors index.
- **Error state**: Logged to console, no redirect.

## Implementation

### 1. Create Alpine module: `resources/js/alpine/author-form.js`

```js
export default function (editAuthorData) {
    return {
        editAuthor: editAuthorData && editAuthorData.id ? editAuthorData : false,
        author: {
            first_name: editAuthorData && editAuthorData.id ? editAuthorData.first_name : '',
            middle_name: editAuthorData && editAuthorData.id ? editAuthorData.middle_name : '',
            last_name: editAuthorData && editAuthorData.id ? editAuthorData.last_name : '',
            id: editAuthorData && editAuthorData.id ? editAuthorData.id : null,
        },
        addAuthor() {
            axios.post(route('authors.store'), {
                author: this.author,
            }).then(response => {
                window.location.replace(route('authors.index'));
            }).catch(error => {
                console.error('Failed to create author:', error.response?.data || error);
            });
        },
        updateAuthor() {
            axios.put(route('authors.update', { author: this.author.id }), {
                author: this.author,
            }).then(response => {
                window.location.replace(route('authors.index'));
            }).catch(error => {
                console.error('Failed to update author:', error.response?.data || error);
            });
        },
    };
}
```

### 2. Create Blade partial: `_form.blade.php`

```blade
<div x-data="authorForm(@js($author))" class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    New Author
                </div>
                <div class="card-body container-fluid">
                    <div class="row">
                        <div class="col-12">
                            Name
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-4">
                            <input name="firstname"
                                    type="text"
                                    class="form-control"
                                    placeholder="First Name"
                                    x-model="author.first_name">
                        </div>
                        <div class="form-group col-4">
                            <input name="middlename"
                                    type="text"
                                    class="form-control"
                                    placeholder="Middle Name"
                                    x-model="author.middle_name">
                        </div>
                        <div class="form-group col-4">
                            <input name="lastname"
                                    type="text"
                                    class="form-control"
                                    placeholder="Last Name"
                                    x-model="author.last_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <button x-show="!editAuthor" type="button" class="btn btn-primary"
                                    x-on:click="addAuthor">
                                <i class="fas fa-plus"></i> Create Author
                            </button>
                            <button x-show="editAuthor" type="button" class="btn btn-primary"
                                    x-on:click="updateAuthor">
                                <i class="fas fa-plus"></i> Update Author
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 3. Update `create.blade.php`

```blade
@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            @include('library.authors._form', ['author' => false])
        </div>
    </div>
@endsection
```

### 4. Update `edit.blade.php`

```blade
@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            @include('library.authors._form', ['author' => $author])
        </div>
    </div>
@endsection
```

### 5. Update `app.js`

Add import at top:
```js
import authorForm from './alpine/author-form';
```

Register:
```js
Alpine.data('authorForm', authorForm);
```

Remove:
```js
Vue.component('author-form', () => import('./components/library/authors/AuthorForm.vue'));
```

### 6. Delete Vue component

Delete `resources/js/components/library/authors/AuthorForm.vue`.

## Acceptance Criteria

1. `npm run build` succeeds
2. `php vendor/bin/phpunit` passes
3. Create author: POST `authors.store`, redirects to authors index
4. Edit author: PUT `authors.update`, redirects to authors index
5. No console errors on either page
6. `AuthorForm.vue` no longer in JS bundle or source

## Implementation Notes

### Resource Route Parameter Names

`authors.update` uses `{ author: this.author.id }` (singular resource name), not `{ id: this.author.id }`.

### `x-on:click` inside `#vue-root`

This form lives inside `#vue-root` (author pages still need it for other Vue components). Use `x-on:click` not `@click`.

### `x-bind:` not `:` for attribute binding

No `:attr` shorthands in this form. If adding bindings later, use `x-bind:` form inside `#vue-root`.

## Edge Cases

- **Edit mode with empty/false author**: Falls through to create mode (Create button shown)
- **Network error on save**: Logged to console, no redirect
- **Empty fields submitted**: Server validation handles it (no client-side — YAGNI)
