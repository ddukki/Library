# Spec 015: BookForm + SelectAuthors → Alpine

**Status:** Implemented
**Applies to:** ADR-0016 (Blade partial + Alpine JS module pattern)

## Goal

Migrate two tightly-coupled Vue 2 components — `BookForm` and `SelectAuthors` — to a single Alpine module. They're always used together (SelectAuthors is only rendered inside BookForm), so consolidating avoids cross-component event plumbing in Alpine.

**Architecture:** One Alpine JS module (`book-form.js`) containing all book form state + author selection + author search/pagination. One Blade template. Shared `_pagination` partial reused.

## Files

| File | Action |
|---|---|
| `resources/views/library/books/create.blade.php` | Modify — replace `<book-form>` with Alpine template |
| `resources/views/library/books/edit.blade.php` | Modify — replace `<book-form>` with Alpine template |
| `resources/views/library/authors/_author-select-row.blade.php` | **Create** — single `<tr>` with check toggle |
| `resources/views/library/authors/_selected-author-badge.blade.php` | **Create** — single `span.badge` with × remove |
| `resources/js/alpine/book-form.js` | **Create** |
| `resources/js/app.js` | Modify — import Alpine module, register, remove Vue registrations |
| `resources/js/components/library/books/BookForm.vue` | Delete |
| `resources/js/components/library/authors/SelectAuthors.vue` | Delete |

## Behavior

### Page (Create/Edit)

- If editing: pre-fills title and selected authors from the book object
- If creating: empty title, no selected authors
- Title input with placeholder "Book Title"
- Author selection card with:
  - Selected authors shown as badges with remove (×) buttons
  - "Select authors for the book from the list of Available Authors!" hint when none selected
  - Search input + search button for available authors
  - Table of authors (name + book badges + select/deselect toggle)
  - Pagination nav at bottom
- Submit button: "Create Book" (create) or "Update Book" (edit)
- On success: redirect to `books.index`
- On error: log to console

### Author Selection

- Search queries `authors.page` API route with `searchColumn=['first_name', 'last_name']`
- Table rows have a check button — blue if selected, outline if not
- Clicking a non-selected author adds them to selected list and shows blue check
- Clicking a selected author removes them
- Selected authors shown as `<span class="badge badge-primary">` with × to remove
- Author display: `first_name middle_name last_name` (space-separated, matching Vue original)

## Implementation

### 1. Update `pagination.js` to read search params dynamically

The pagination composable currently captures `searchTerm` and `searchColumn` in closure at creation time. This means `getPage()` always uses the initial values, not the current Alpine state. Fix: read `this.searchTerm` and `this.searchColumn` at call time.

```js
export default function (config) {
    const paginationRoute = config.paginationRoute;

    return {
        page: null,
        loaded: false,

        getPage(n) {
            axios.get(route(paginationRoute, {
                page: n,
                searchColumn: this.searchColumn || [],
                searchTerm: this.searchTerm || '',
                perPage: 10,
            })).then(response => {
                if (response.data) {
                    this.page = response.data.page;
                    this.loaded = true;
                }
            }).catch(error => {
                console.error('Failed to load page:', error.response?.data || error);
            });
        },

        isCurrentPage(n) {
            return this.page && this.page.current_page === n;
        },
    };
}
```

**Why:** `this.searchColumn` and `this.searchTerm` resolve to the Alpine component's reactive state when `getPage()` is called. The consumer sets these properties and they're read dynamically — no stale closure values.

**Impact:** This change must also be reflected in spec_012. All consumers (all-authors, all-books, and now book-form) rely on dynamic search params.

### 2. Create `resources/js/alpine/book-form.js`

```js
import withPagination from './pagination';

export default function (bookData, initialAuthorsPage) {
    const pagination = withPagination({
        paginationRoute: 'authors.page',
    });

    return {
        ...pagination,

        editing: !!bookData,
        book: {
            id: bookData?.id || null,
            title: bookData?.title || '',
            authors: bookData?.authors || [],
        },

        searchTerm: '',
        searchColumn: ['first_name', 'last_name'],

        init() {
            if (initialAuthorsPage) {
                this.page = initialAuthorsPage;
            }
        },

        get authors() {
            return this.page ? this.page.data : [];
        },

        searchAuthors() {
            this.getPage(1);
        },

        isSelected(author) {
            return this.book.authors.some(function (a) {
                return a.id == author.id;
            });
        },

        selectAuthor(author) {
            if (!this.isSelected(author)) {
                this.book.authors.push(author);
            }
        },

        unselectAuthor(author) {
            var index = -1;
            this.book.authors.forEach(function (a, i) {
                if (a.id == author.id) {
                    index = i;
                }
            });
            if (index > -1) {
                this.book.authors.splice(index, 1);
            }
        },

        createBook() {
            axios.post(route('books.store'), {
                book: {
                    title: this.book.title,
                    authors: this.book.authors,
                },
            }).then(function (response) {
                window.location.replace(route('books.index'));
            }).catch(function (error) {
                console.error('Failed to create book:', error.response?.data || error);
            });
        },

        updateBook() {
            axios.put(route('books.update', { book: this.book.id }), {
                book: {
                    title: this.book.title,
                    authors: this.book.authors,
                },
            }).then(function (response) {
                window.location.replace(route('books.index'));
            }).catch(function (error) {
                console.error('Failed to update book:', error.response?.data || error);
            });
        },
    };
}
```

**Note:** Uses regular `function` expressions (not arrow functions) inside `forEach`, `some`, and `then` callbacks because arrow functions would capture `this` from the outer scope (Alpine component), which is the desired behavior. However, in the `forEach` and `some` callbacks, `function(a, i)` with `return` is safe because we're not using `this` inside those callbacks.

Actually, arrow functions are fine here since they correctly capture `this`. Let me use arrow functions for consistency with modern JS style and to avoid confusion.

### 3. Create `_selected-author-badge.blade.php`

A single selected-author badge with × remove button. Used inside Alpine `x-for`.

```blade
<span class="badge badge-primary mr-2">
    <span x-text="`${ {{ $item }}.first_name } ${ {{ $item }}.middle_name } ${ {{ $item }}.last_name }`"></span>
    <a href="#" x-on:click.prevent="unselectAuthor( {{ $item }} )">
        <i class="fas fa-times-circle"></i>
    </a>
</span>
```

Usage:
```blade
<template x-for="(selected, index) in book.authors" :key="index">
    @include('library.authors._selected-author-badge', ['item' => 'selected'])
</template>
```

### 4. Create `_author-select-row.blade.php`

A single table row for an available author, with check toggle, name, and book badges.

```blade
<tr>
    <td>
        <button x-show="!isSelected( {{ $item }} )"
                class="btn btn-sm btn-outline-primary"
                x-on:click.prevent="selectAuthor( {{ $item }} )">
            <i class="fas fa-check"></i>
        </button>
        <button x-show="isSelected( {{ $item }} )"
                class="btn btn-sm btn-primary"
                x-on:click.prevent="unselectAuthor( {{ $item }} )">
            <i class="fas fa-check"></i>
        </button>
    </td>
    <td x-text="`${ {{ $item }}.first_name } ${ {{ $item }}.middle_name } ${ {{ $item }}.last_name }`"></td>
    <td>
        <template x-for="(abook, n) in ( {{ $item }}.books || [])" :key="n">
            <span class="badge badge-primary mr-1" x-text="abook.title"></span>
        </template>
    </td>
</tr>
```

Usage:
```blade
<template x-for="(author, index) in authors" :key="index">
    @include('library.authors._author-select-row', ['item' => 'author'])
</template>
```

### 5. Update `create.blade.php`

```blade
@extends('layouts.library', ['useVueRoot' => false])

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12"
                 x-data="bookForm(null, @js($authors))">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header">Book Information</div>
                                <div class="card-body">
                                    <input id="title" name="title"
                                            type="text" class="form-control"
                                            placeholder="Book Title"
                                            x-model="book.title">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header">Book Author(s)</div>
                                <div class="card-body">
                                    <p x-show="book.authors.length == 0">
                                        Select authors for the book from the list of <b>Available Authors</b>!
                                    </p>
                                    <h3>
                                        <template x-for="(selected, index) in book.authors" :key="index">
                                            @include('library.authors._selected-author-badge', ['item' => 'selected'])
                                        </template>
                                    </h3>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                                x-model="searchTerm"
                                                x-on:keyup.enter="searchAuthors"
                                                placeholder="Search Available Authors">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" x-on:click.prevent="searchAuthors">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                            <th scope="col"></th>
                                            <th scope="col">Author Name</th>
                                            <th scope="col">Books</th>
                                        </thead>
                                        <template x-for="(author, index) in authors" :key="index">
                                            @include('library.authors._author-select-row', ['item' => 'author'])
                                        </template>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    @include('library.partials._pagination')
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="form-group col-12">
                                    <button x-show="!editing"
                                            class="btn btn-primary btn-lg"
                                            x-on:click.prevent="createBook">
                                        Create Book
                                    </button>
                                    <button x-show="editing"
                                            class="btn btn-primary btn-lg"
                                            x-on:click.prevent="updateBook">
                                        Update Book
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```

### 6. Update `edit.blade.php`

Same template, different `x-data` init:

```blade
@extends('layouts.library', ['useVueRoot' => false])

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12"
                 x-data="bookForm(@js($book), @js($authors))">
                {{-- Identical to create.blade.php body --}}
            </div>
        </div>
    </div>
@endsection
```

### 7. Update `app.js`

Add import:
```js
import bookForm from './alpine/book-form';
```

Register:
```js
Alpine.data('bookForm', bookForm);
```

Remove:
```js
Vue.component('book-form', () => import('./components/library/books/BookForm.vue'));
Vue.component('select-authors', () => import('./components/library/authors/SelectAuthors.vue'));
```

### 8. Delete Vue files

- `resources/js/components/library/books/BookForm.vue`
- `resources/js/components/library/authors/SelectAuthors.vue`

## Acceptance Criteria

1. `npm run build` succeeds
2. `php vendor/bin/phpunit` passes
3. Book create page loads: title input, author search, paginated table, "Create Book" button
4. Book edit page loads: pre-filled title, pre-selected authors, "Update Book" button
5. Author search returns results, pagination works
6. Click author row → check button turns blue, author appears in badges
7. Click selected author → removed from badges, check button turns outline
8. × on badge → removes author from selection
9. "Create Book" → POST to `books.store`, redirects to `books.index`
10. "Update Book" → PUT to `books.update`, redirects to `books.index`
11. No console errors
12. `book-form` and `select-authors` no longer in JS bundle or source

## Implementation Notes

### Single module, not two

SelectAuthors is only used inside BookForm. A separate Alpine module would require cross-component event handling (`$dispatch`/`$watch`) for the shared `selectedAuthors` array. Consolidating into one module avoids this complexity — all state is in one `x-data` scope.

### `useVueRoot => false`

The page uses `<template x-for>` in the author table, pagination, and selected badges — all of which conflict with Vue 2's template compiler. Set `useVueRoot => false` on both create and edit layouts.

### Extracted partials via `$item` convention

The author table row and selected-author badge are extracted into partials using the `$item` convention (same as Spec 013 card partials). This keeps `create.blade.php` and `edit.blade.php` clean:

```blade
{{-- Selected badge --}}
<template x-for="(selected, index) in book.authors" :key="index">
    @include('library.authors._selected-author-badge', ['item' => 'selected'])
</template>

{{-- Author select row --}}
<template x-for="(author, index) in authors" :key="index">
    @include('library.authors._author-select-row', ['item' => 'author'])
</template>
```

The partials reference Alpine component methods (`isSelected()`, `selectAuthor()`, `unselectAuthor()`) via `{{ $item }}`, which Blade compiles to the Alpine loop variable name.

### Edit page uses same body as create

`edit.blade.php` only changes the `x-data` init. The template body is identical. For now, accept the duplication — both files are small (~15 lines). If a third variant appears, extract a shared `_book-form-body.blade.php`.

### Pagination composable reused

Book form uses `withPagination` from `pagination.js`. The `page` and `loaded` state from the composable is shared with the book-form's author table. The `searchTerm` and `searchColumn` are set on the component and read dynamically by `getPage()`.

### Initial author data pre-loaded

The `BookController::create()` and `edit()` methods already pass `$authors` (paginated author list). Book form receives it as `initialAuthorsPage` and sets `this.page` in `init()` — no initial API call needed.

### Resource route parameter names

```js
route('books.update', { book: this.book.id })   // not { id: ... }
route('books.store')                             // no params
route('books.index')                             // no params
```

### Author name format

Matches Vue original: `first_name middle_name last_name` (space-separated, no null filtering). A null `middle_name` produces a double space — matching original behavior.

### `isSelected()` uses `==` not `===`

The `id` from the API response may be a number while `author.id` from the initial page data could be a string. Use `==` for cross-type comparison (consistent with spec_011 fix pattern).

### `getPage()` in pagination.js reads `this`

The updated pagination composable uses `this.searchTerm` and `this.searchColumn` (Alpine component scope) instead of closure-captured values. This means:
- Multiple components can use the composable with different search terms
- Search terms are always current (not stale)
- The `paginationRoute` is still captured in closure (it's constant)

## Edge Cases

- **No authors in search**: Empty table body, pagination nav hidden
- **Author already selected**: Check button shows blue (primary), clicking unselects
- **All authors selected**: All check buttons show blue, each click unselects
- **Empty search**: Reloads page 1 with all authors
- **Rapid search clicks**: Each fires its own request; last-response-wins
- **Network error on create/update**: Logged to console
- **Book with no title**: Sent to API as empty string (server-side validation — skipping validation spec for now)
- **Edit with no authors**: `book.authors` is an empty array, all authors are available for selection
