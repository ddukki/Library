# Spec 011: Alpine.js — Book Editions Migration

**Status:** Approved (implemented)
**Applies to:** ADR-0002 (Alpine.js over Vue 2), ADR-0004 (Bootstrap SCSS kept until migration done), ADR-0016 (Blade partial + Alpine JS module pattern)

## Goal

Migrate the Vue 2 `Editions` component tree to Alpine.js using the ADR-0016 pattern. This includes the parent `Editions`, child `EditionRow`, and grandchild `ShelveEdition` — all three registered independently in Vue but tightly coupled.

**Architecture:** Two Alpine modules composed together — `book-editions.js` (editions CRUD + row editing) imports and spreads `book-editions-shelve.js` (shelving state + methods). Per-row state tracked via arrays indexed by loop position. Three Blade partials — child partials inherit `x-for` scope, no nested `x-data`.

## Files

| File | Action |
|---|---|
| `resources/views/library/books/show.blade.php` | Modify — replace `<book-editions>` with `@include`, set `useVueRoot => false` |
| `resources/views/library/editions/_editions.blade.php` | **Create** — outer container + header row + add form |
| `resources/views/library/editions/_row.blade.php` | **Create** — per-edition display/edit row |
| `resources/views/library/editions/_shelve.blade.php` | **Create** — shelving badge UI |
| `resources/js/alpine/book-editions.js` | **Create** — Alpine `data()` function (editions CRUD) |
| `resources/js/alpine/book-editions-shelve.js` | **Create** — shelving state + methods, imported by book-editions.js |
| `resources/js/app.js` | Modify — import Alpine module, remove 3 Vue registrations |
| `resources/js/components/library/editions/Editions.vue` | Delete |
| `resources/js/components/library/editions/EditionRow.vue` | Delete |
| `resources/js/components/library/editions/ShelveEdition.vue` | Delete |

## Behavior

### Editions (parent)

- Receives book data from Blade (`@js($book)`)
- Fetches `locationTypes` from `locationtypes.all` on init
- Shows header row: Name / Format / Size / Shelves / Actions
- Iterates editions, rendering one row per edition
- "Add Edition" button toggles add form visibility
- Add form: name input, type select (from locationTypes), size input
- Submit POST to `editions.store`, pushes response to local editions array, hides form

### EditionRow (per row)

- **Display mode**: Shows edition name (link), location type, size, shelf badges, edit/delete buttons
- **Edit mode**: Input fields replace display text, save/cancel buttons replace edit button
- Edit toggles local `editing` state per row
- Save: PUT to `editions.update`, updates local data, exits edit mode
- Delete: DELETE to `editions.destroy`, emits event to parent (removes row)

### ShelveEdition (per row, inside shelve column)

- Shows shelf badges (linked) for the edition
- "Edit shelves" toggle switches to shelving mode
- Shelving mode: shelf select dropdown (fetched from `shelves.user`) + add button
- Add: POST to `editions.shelve`, pushes shelf to local shelves array
- Remove (X on badge): POST to `editions.unshelve`, splices from local shelves array
- Editing state synced with parent row editing state

## Implementation

### 1. Create shelving module: `resources/js/alpine/book-editions-shelve.js`

```js
export default {
    userShelves: [],
    shelveEditRows: [],
    selectedShelf: null,

    toggleShelveEdit(index) {
        this.shelveEditRows[index] = !this.shelveEditRows[index];
    },

    shelfURL(shelf) {
        return route('shelves.show', { shelf: shelf.id });
    },

    isShelved(shelf, editionShelves) {
        return editionShelves.some(s => s.id === shelf.id);
    },

    shelveEdition(editionIndex, shelf) {
        const edition = this.editions[editionIndex];
        if (this.isShelved(shelf, edition.shelves || [])) return;

        axios.post(route('editions.shelve', {
            edition: edition.id,
            shelf: shelf.id,
        })).then(response => {
            if (!this.editions[editionIndex].shelves) {
                this.editions[editionIndex].shelves = [];
            }
            this.editions[editionIndex].shelves.push(shelf);
        }).catch(error => {});
    },

    unshelveEdition(editionIndex, shelfIndex) {
        const edition = this.editions[editionIndex];
        axios.post(route('editions.unshelve', {
            edition: edition.id,
            shelf: edition.shelves[shelfIndex].id,
        })).then(response => {
            this.editions[editionIndex].shelves.splice(shelfIndex, 1);
        }).catch(error => {});
    },
};
```

### 2. Create main Alpine module: `resources/js/alpine/book-editions.js`

```js
import shelving from './book-editions-shelve';

export default function (bookData) {
    const editions = (bookData.editions || []).map(e => ({ ...e }));

    return {
        editions: editions,
        locationTypes: [],
        newEdition: { name: '', type: null, size: 0 },
        showAddForm: false,
        editingRows: editions.map(() => false),
        editData: editions.map(e => ({ ...e })),

        ...shelving,

        init() {
            axios.get(route('locationtypes.all')).then(response => {
                this.locationTypes = response.data.locationTypes;
            }).catch(error => {});
            axios.get(route('shelves.user')).then(response => {
                this.userShelves = response.data.shelves;
            }).catch(error => {});
            this.shelveEditRows = this.editions.map(() => false);
        },

        toggleAddForm() {
            this.showAddForm = !this.showAddForm;
        },

        addEdition() {
            axios.post(route('editions.store'), {
                book: bookData,
                edition: this.newEdition,
            }).then(response => {
                this.editions.push(response.data.added);
                this.editingRows.push(false);
                this.editData.push({ ...response.data.added });
                this.newEdition = { name: '', type: null, size: 0 };
                this.showAddForm = false;
            }).catch(error => {});
        },

        deleteEdition(index) {
            axios.delete(route('editions.destroy', { edition: this.editions[index].id })).then(response => {
                this.editions.splice(index, 1);
                this.editingRows.splice(index, 1);
                this.editData.splice(index, 1);
            }).catch(error => {});
        },

        toggleEdit(index) {
            this.editingRows[index] = !this.editingRows[index];
            this.editData[index] = { ...this.editions[index] };
        },

        updateEdition(index) {
            axios.put(route('editions.update', { edition: this.editions[index].id }), {
                edition: this.editData[index],
            }).then(response => {
                this.editions[index] = { ...this.editData[index] };
                this.editingRows[index] = false;
            }).catch(error => {});
        },

        editionURL(edition) {
            return route('editions.show', { edition: edition.id });
        },
    };
}
```

### 3. Create Blade partial: `_shelve.blade.php`

```blade
<div>
    <div x-show="shelveEditRows[index]" class="row no-gutters mb-2">
        <div class="col-10">
            <select class="custom-select custom-select-sm"
                    x-model="selectedShelf">
                <template x-for="userShelf in userShelves">
                    <option :value="userShelf" x-text="userShelf.name"></option>
                </template>
            </select>
        </div>
        <div class="col-2">
            <button class="btn btn-primary btn-sm ml-2" x-on:click="shelveEdition(index, selectedShelf)">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-12">
            <template x-for="(shelf, sIndex) in (edition.shelves || [])">
                <span class="badge badge-pill badge-primary">
                    <a class="text-light mr-1" x-bind:href="shelfURL(shelf)" x-text="shelf.name"></a>
                    <a x-show="shelveEditRows[index]" x-on:click="unshelveEdition(index, sIndex)">
                        <i class="fas fa-times-circle"></i>
                    </a>
                </span>
            </template>
            <a x-show="!shelveEditRows[index]" class="badge badge-pill badge-primary text-light"
                    x-on:click.prevent="toggleShelveEdit(index)">
                <i class="fas fa-edit"></i>
            </a>
            <a x-show="shelveEditRows[index]" class="badge badge-pill badge-danger text-light"
                    x-on:click.prevent="toggleShelveEdit(index)">
                <i class="fas fa-edit"></i>
            </a>
        </div>
    </div>
</div>
```

### 4. Create Blade partial: `_row.blade.php`

```blade
<div class="row border-bottom mt-1">
    <div class="col-3">
        <a class="small" x-bind:href="editionURL(edition)" x-show="!editingRows[index]" x-text="editData[index].name"></a>
        <input x-show="editingRows[index]" class="form-control form-control-sm"
                type="text"
                x-model="editData[index].name">
    </div>
    <div class="col-2">
        <p class="small" x-show="!editingRows[index]" x-text="editData[index].location_type?.name"></p>
        <select x-show="editingRows[index]" class="form-control form-control-sm"
                x-model="editData[index].location_type">
            <template x-for="type in locationTypes">
                <option :value="type" x-text="type.name"></option>
            </template>
        </select>
    </div>
    <div class="col-2">
        <p class="small" x-show="!editingRows[index]" x-text="editData[index].location_size"></p>
        <input x-show="editingRows[index]" class="form-control form-control-sm"
                type="text" x-model="editData[index].location_size">
    </div>
    <div class="col-3">
        @include('library.editions._shelve')
    </div>
    <div class="col-2">
        <button x-show="!editingRows[index]" class="btn btn-sm btn-primary" x-on:click="toggleEdit(index)">
            <i class="fas fa-edit"></i>
        </button>
        <div x-show="editingRows[index]" class="btn-group" role="group">
            <button class="btn btn-primary btn-sm" x-on:click="updateEdition(index)">
                <i class="fas fa-save"></i>
            </button>
            <button class="btn btn-secondary btn-sm" x-on:click="toggleEdit(index)">
                <i class="fas fa-window-close"></i>
            </button>
        </div>
        <button class="btn btn-sm btn-danger" x-on:click="deleteEdition(index)">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
</div>
```

### 5. Create Blade partial: `_editions.blade.php`

```blade
<div x-data="bookEditions(@js($book))" class="row">
    <div class="col-12">
        <div class="row border-bottom">
            <div class="col-3">Name</div>
            <div class="col-2">Format</div>
            <div class="col-2">Size</div>
            <div class="col-3">Shelves</div>
            <div class="col-2">Actions</div>
        </div>

        <template x-for="(edition, index) in editions">
            @include('library.editions._row')
        </template>

        <button x-on:click="toggleAddForm"
                class="btn btn-primary mt-2" role="button">
            <i class="fas fa-plus"></i> Add Edition
        </button>

        <div x-show="showAddForm" class="row mt-3">
            <div class="col-12">
                <div class="form-group">
                    <label for="name">Edition Name</label>
                    <input type="text" id="name"
                            class="form-control"
                            placeholder="Edition Name"
                            x-model="newEdition.name">
                </div>
                <div class="form-group">
                    <label for="type">Edition Type</label>
                    <select class="form-control"
                            id="locationTypes"
                            x-model="newEdition.type">
                        <template x-for="type in locationTypes">
                            <option :value="type" x-text="type.name"></option>
                        </template>
                    </select>
                </div>
                <div class="form-group">
                    <label for="size">Edition Size</label>
                    <input type="text" id="size"
                            class="form-control"
                            placeholder="Size"
                            x-model="newEdition.size">
                </div>
                <button x-on:click="addEdition"
                        class="btn btn-primary" role="button">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>
```

### 6. Update `show.blade.php`

```blade
@extends('layouts.library', ['useVueRoot' => false])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-3">
                <h2>{{ $book->title }}</h2>
                <h5>
                    @php
                        $authors = [];
                        foreach($book->authors as $author) {
                            array_push($authors, $author->first_name.' '.$author->middle_name.' '.$author->last_name);
                        }
                        $authorList = implode(', ', $authors);
                    @endphp
                    {{ $authorList }}
                </h5>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Book Information
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        Editions
                    </div>
                    <div class="card-body">
                        <div class="container">
                            @include('library.editions._editions')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```

### 7. Update `app.js`

Add import at top:
```js
import bookEditions from './alpine/book-editions';
```

Register:
```js
Alpine.data('bookEditions', bookEditions);
```

Remove:
```js
Vue.component('book-editions', () => import('./components/library/editions/Editions.vue'));
Vue.component('edition-row', () => import('./components/library/editions/EditionRow.vue'));
Vue.component('shelve-edition', () => import('./components/library/editions/ShelveEdition.vue'));
```

### 8. Delete Vue files

Delete:
- `resources/js/components/library/editions/Editions.vue`
- `resources/js/components/library/editions/EditionRow.vue`
- `resources/js/components/library/editions/ShelveEdition.vue`

## Acceptance Criteria

1. `npm run build` succeeds
2. `php vendor/bin/phpunit` passes
3. Book show page loads with editions table
4. Edition name links to edition show page
5. Edit mode: inputs replace text, save/cancel work
6. Delete removes edition from display
7. Add Edition toggles form, submit adds edition
8. Shelve badges show, edit shelves toggle works
9. Add/remove shelf via dropdown works
10. No console errors
11. Three Vue files no longer in JS bundle or source

## Implementation Notes

### Per-row state tracked via arrays

Since Alpine `x-for` doesn't support per-iteration reactive objects naturally, per-row state (editing, edit data, shelve edit) is managed with parallel arrays indexed by loop index.

### `x-bind:` inside `#vue-root`

This page has `useVueRoot => false` due to `x-for`, so `:attr` shorthand is safe. But use `x-bind:` for consistency with migrated patterns.

### `_shelve.blade.php` needs `selectedShelf`

The shelve select needs a local variable. Since `_shelve` is included inside `x-for`, the variable is scoped at the parent Alpine data level and shared across rows. Each row's shelve add uses the current row index to determine which edition to shelve.

### Resource Route Parameter Names

- `editions.update` → `{ edition: id }`
- `editions.destroy` → `{ edition: id }`
- `editions.shelve` → `{ edition: id, shelf: id }`
- `editions.unshelve` → `{ edition: id, shelf: id }`
- `editions.show` → `{ edition: id }`
- `shelves.show` → `{ shelf: id }`

### Alpine `<select>` with object values

Alpine does not support objects as `<option :value="object">` — HTML option values are always strings, and Alpine's `x-model` serializes the object to `"[object Object]"`. Vue 2 handles this via reference matching.

**Fix:** Use `:value="object.id"` (scalar) and look up the full object from the local array when needed. For add form: `newEdition.type_id` + `:value="type.id"`, then `type_id` is sent directly to the controller. For edit form: same pattern with `location_type_id`. For shelve select: `selectedShelf` stores a shelf ID, `shelveEdition()` looks up the shelf from `userShelves`.

## Edge Cases

- **No editions**: Header row + Add button only
- **Edition with null location_type**: Empty format cell
- **Network error on add/edit/delete/shelve/unshelve**: Logged, no UI change
- **Add form visibility**: Starts hidden, toggles on button click
