# Spec 014: AllAuthors + AllBooks → Alpine Pages

**Status:** Implemented
**Applies to:** ADR-0002, ADR-0016, ADR-0017

## Goal

Migrate the Vue 2 `AllAuthors` and `AllBooks` page-level components to Alpine.js. Both are searchable, paginated lists with nearly identical structure — consolidated into one spec.

**Architecture:** Each page gets its own Alpine JS module (composing pagination), one Blade template, and uses the card partials from Spec 013. The pagination composable from Spec 012 is spread into the Alpine data.

## Files

| File | Action |
|---|---|
| `resources/views/library/authors/index.blade.php` | Modify — replace `<all-authors>` with Alpine template |
| `resources/views/library/books/index.blade.php` | Modify — replace `<all-books>` with Alpine template |
| `resources/js/alpine/all-authors.js` | **Create** |
| `resources/js/alpine/all-books.js` | **Create** |
| `resources/js/app.js` | Modify — import 2 Alpine modules, remove 2 Vue registrations |
| `resources/js/components/library/authors/AllAuthors.vue` | Delete |
| `resources/js/components/library/books/AllBooks.vue` | Delete |
| `resources/js/components/library/Pagination.vue` | Delete (last consumer removed) |
| `resources/js/components/library/authors/AuthorCard.vue` | Delete (deferred from Spec 013) |
| `resources/js/components/library/books/BookCard.vue` | Delete (deferred from Spec 013) |

## Behavior

### AllAuthors

- Search input + search button
- "Add New" button linking to `authors.create`
- Grid of author cards (from `_author-card.blade.php`)
- Pagination nav at bottom
- Initial data: `searchTerm` and `searchColumn` from Blade props
- On mount: fetches page 1 from `authors.page`
- Search triggers fetch of page 1 with search params
- Uses `pagination` composable from Spec 012

### AllBooks

- Search input + search button
- "Add New" button linking to `books.create`
- Grid of book cards (from `_book-card.blade.php`)
- Pagination nav at bottom
- Initial data: `searchTerm` and `searchColumn` from Blade props
- On mount: fetches page 1 from `books.page`
- Search triggers fetch of page 1 with search params
- Uses `pagination` composable from Spec 012

## Implementation

### 1. Create `resources/js/alpine/all-authors.js`

```js
import withPagination from './pagination';

export default function (initialSearchTerm, initialSearchColumn) {
    const pagination = withPagination({
        paginationRoute: 'authors.page',
        searchColumn: initialSearchColumn.length > 0 ? initialSearchColumn : ['first_name', 'middle_name', 'last_name'],
    });

    return {
        ...pagination,
        searchTerm: initialSearchTerm || '',
        searchColumn: initialSearchColumn.length > 0 ? initialSearchColumn : ['first_name', 'middle_name', 'last_name'],

        init() {
            this.getPage(1);
        },

        search() {
            this.getPage(1);
        },

        get authors() {
            return this.page ? this.page.data : [];
        },
    };
}
```

### 2. Create `resources/js/alpine/all-books.js`

```js
import withPagination from './pagination';

export default function (initialSearchTerm, initialSearchColumn) {
    const pagination = withPagination({
        paginationRoute: 'books.page',
        searchColumn: initialSearchColumn.length > 0 ? initialSearchColumn : ['title'],
    });

    return {
        ...pagination,
        searchTerm: initialSearchTerm || '',
        searchColumn: initialSearchColumn.length > 0 ? initialSearchColumn : ['title'],

        init() {
            this.getPage(1);
        },

        search() {
            this.getPage(1);
        },

        get books() {
            return this.page ? this.page.data : [];
        },
    };
}
```

### 3. Update `authors/index.blade.php`

```blade
@extends('layouts.library')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="container-fluid"
                     x-data="allAuthors(@js($searchTerm ?? ''), @js($searchColumn ?? []))">
                    <div class="row">
                        <div class="col-10">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"
                                        placeholder="Search Authors"
                                        aria-label="Search for authors"
                                        x-model="searchTerm">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" x-on:click.prevent="search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <a x-bind:href="route('authors.create')"
                                    class="btn btn-primary"
                                    role="button">
                                <i class="fas fa-plus"></i> Add New
                            </a>
                        </div>
                    </div>
                    <template x-for="(author, index) in authors" :key="index">
                        @include('library.authors._author-card', ['item' => 'author'])
                    </template>
                    <div class="row">
                        <div class="col-12">
                            @include('library.partials._pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```

### 4. Update `books/index.blade.php`

```blade
@extends('layouts.library')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="container-fluid"
                     x-data="allBooks(@js($searchTerm ?? ''), @js($searchColumn ?? []))">
                    <div class="row">
                        <div class="col-10">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"
                                        placeholder="Search Books"
                                        aria-label="Search for books"
                                        x-model="searchTerm">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" x-on:click.prevent="search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <a x-bind:href="route('books.create')"
                                    class="btn btn-primary"
                                    role="button">
                                <i class="fas fa-plus"></i> Add New
                            </a>
                        </div>
                    </div>
                    <template x-for="(book, index) in books" :key="index">
                        @include('library.books._book-card', ['item' => 'book'])
                    </template>
                    <div class="row">
                        <div class="col-12">
                            @include('library.partials._pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```

### 5. Update `app.js`

Add imports:
```js
import allAuthors from './alpine/all-authors';
import allBooks from './alpine/all-books';
```

Register:
```js
Alpine.data('allAuthors', allAuthors);
Alpine.data('allBooks', allBooks);
```

Remove:
```js
Vue.component('all-authors', () => import('./components/library/authors/AllAuthors.vue'));
Vue.component('author-card', () => import('./components/library/authors/AuthorCard.vue'));
Vue.component('all-books', () => import('./components/library/books/AllBooks.vue'));
Vue.component('book-card', () => import('./components/library/books/BookCard.vue'));
Vue.component('pagination-vue', () => import('./components/library/Pagination.vue'));
```

### 6. Delete Vue files

- `resources/js/components/library/authors/AllAuthors.vue`
- `resources/js/components/library/authors/AuthorCard.vue` (deferred from Spec 013)
- `resources/js/components/library/books/AllBooks.vue`
- `resources/js/components/library/books/BookCard.vue` (deferred from Spec 013)
- `resources/js/components/library/Pagination.vue`

## Acceptance Criteria

1. `npm run build` succeeds
2. `php vendor/bin/phpunit` passes
3. Authors page loads author cards with search and pagination
4. Books page loads book cards with search and pagination
5. Search filters results via API
6. Pagination nav shows only when >1 page
7. "Add New" links to correct create page
8. No console errors
9. `all-authors`, `all-books`, `author-card`, `book-card`, `pagination-vue` no longer in JS bundle or source

## Implementation Notes

### `@include` inside `x-for` via `$item` convention

Blade `@include` is compile-time. Inside Alpine `x-for`, the included partial cannot reference PHP variables like `$author` — they don't exist in Alpine's runtime scope.

**Fix:** The card partials use a `$item` convention (see Spec 013). The parent passes the Alpine loop variable name as a string:

```blade
@include('library.authors._author-card', ['item' => 'author'])
```

Inside the partial, `{{ $item }}` outputs the literal string `author`, which Alpine evaluates as the loop variable. This produces valid Alpine directives per iteration without inlining HTML.

### `route()` helper available globally

The Ziggy `route()` helper is injected via `@routes` in the layout and is available globally in JavaScript. Alpine can call `route('authors.edit', { author: author.id })` directly.

### Pagination spread order

Spread pagination **first**, define the consumer's `init()` **after** — the last `init()` wins in Alpine. The pagination composable has no `init()` (per Spec 012 guidance), so only the consumer's `init()` runs.

### `#vue-root` removal (Spec 016 dependency)

These pages were the last consumers of `#vue-root` with `useVueRoot => false`. After Spec 016 removes Vue from the build chain entirely, `#vue-root` and the `$useVueRoot` mechanism are deleted from the layouts, and `useVueRoot => false` is removed from both index templates.

### Search flow

1. User types in search input (`x-model="searchTerm"`)
2. User clicks search button or presses enter
3. `search()` calls `this.getPage(1)` — resets to page 1 with current `searchTerm` and `searchColumn`
4. The pagination composable's `getPage()` sends the params to the API
5. Response updates `this.page`, which reactively updates the card loop

### Resource Route Parameter Names

```blade
{{ route('authors.create') }}                             {{-- no params --}}
{{ route('books.create') }}                               {{-- no params --}}
route('authors.edit', { author: author.id })              {{-- not { id: ... } --}}
route('books.show', { book: book.id })                    {{-- not { id: ... } --}}
route('books.edit', { book: book.id })                    {{-- not { id: ... } --}}
```

### Per-page default search columns

- Authors: `['first_name', 'middle_name', 'last_name']`
- Books: `['title']`

These match the Vue 2 components. The `initialSearchColumn` prop from Blade overrides if provided.

### Computed `authors`/`books`

Both modules expose a getter that returns `this.page.data` (or `[]` before the first load). The template iterates over this array.

## Edge Cases

- **Empty results**: No cards rendered, pagination nav hidden (last_page = 0)
- **Single page**: Pagination nav hidden (last_page = 1)
- **No search term**: Initial load fetches all records (page 1, empty searchTerm)
- **Network error**: Logged to console by pagination composable's catch handler
- **Rapid search clicks**: Each click fires its own request; last-response-wins
