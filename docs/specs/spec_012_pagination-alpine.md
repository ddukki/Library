# Spec 012: Alpine.js — Pagination Composable

**Status:** Implemented
**Applies to:** ADR-0002 (Alpine.js over Vue 2), ADR-0004 (Bootstrap SCSS kept until migration done), ADR-0016 (Blade partial + Alpine JS module pattern)

## Goal

Migrate the Vue 2 `Pagination.vue` component to a reusable Alpine composable. Pagination is not a standalone component — it's a data-fetching + rendering pattern used by three consumer components (AllBooks, AllAuthors, SelectAuthors). In Alpine, it becomes a composable function that any Alpine module can spread, plus a Blade partial for the HTML.

**Architecture:** Single JS module exporting a factory function that returns pagination state + methods. Blade partial renders the pagination `<nav>`. Consumer components call the factory in their `x-data` function and include the partial in their Blade template.

## Files

| File | Action |
|---|---|
| `resources/js/alpine/pagination.js` | **Create** — composable factory function |
| `resources/views/library/partials/_pagination.blade.php` | **Create** — pagination nav partial |
| `resources/js/components/library/Pagination.vue` | Delete (after all consumers migrated) |
| `resources/js/app.js` | Modify — remove `pagination-vue` Vue registration (after all consumers migrated) |

**Note:** The Vue `Pagination.vue` file and its registration must remain until **all three consumers** (AllBooks, AllAuthors, SelectAuthors) are migrated, since they each depend on it. This spec only migrates the Pagination module itself; consumer migration is in subsequent specs.

## Behavior

### Composable contract

The pagination factory returns an object with:

| Property | Type | Description |
|---|---|---|
| `page` | Object\|null | Paginated response from the API (`{ data, current_page, last_page, total, ... }`) |
| `loaded` | Boolean | True after first successful fetch |
| `getPage(n)` | Method | Fetch page `n` from the API, sets `this.page` |
| `isCurrentPage(n)` | Method | Returns true if `n` equals the current page number |
| `totalPages` | Computed | `page.last_page` or 0 |
| `hasPages` | Computed | `page.last_page > 1` |

### Lifecycle

- `init()` calls `this.getPage(1)` automatically
- Each page click calls `this.getPage(n)` with the clicked page number
- After fetch, `this.page` is updated; the template reactively re-renders

### Pagination nav HTML

- Renders only when `page && page.last_page > 1`
- Unordered list of page links, Bootstrap `pagination` class
- Current page gets `page-item active` class
- Click handler on each link calls `getPage(n)`

## Implementation

### 1. Create composable: `resources/js/alpine/pagination.js`

```js
export default function (config) {
    const paginationRoute = config.paginationRoute;
    const searchColumn = config.searchColumn || [];
    const searchTerm = config.searchTerm || '';

    return {
        page: null,
        loaded: false,

        init() {
            this.getPage(1);
        },

        getPage(n) {
            axios.get(route(paginationRoute, {
                page: n,
                searchColumn: searchColumn,
                searchTerm: searchTerm,
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

### 2. Create Blade partial: `_pagination.blade.php`

```blade
<nav x-show="page && page.last_page > 1" class="mt-3">
    <ul class="pagination justify-content-center" role="navigation">
        <template x-for="n in page.last_page" :key="n">
            <li x-bind:class="isCurrentPage(n) ? 'page-item active' : 'page-item'">
                <a class="page-link" href="#" x-on:click.prevent="getPage(n)"
                   x-text="n">
                </a>
            </li>
        </template>
    </ul>
</nav>
```

## Acceptance Criteria

1. `npm run build` succeeds
2. Composable exports a function, not a plain object (matches Alpine `data()` pattern)
3. `getPage()` makes an axios GET to the configured route with page/searchColumn/searchTerm/perPage params
4. Nav only renders when pagination has more than 1 page
5. Current page highlighted with `active` class
6. Page `x-for` uses `:key` for Alpine key attribute (for proper re-rendering)
7. No Vue `Pagination.vue` component loaded (verify after all consumers migrated — this spec only)

## Implementation Notes

### Not a standalone Alpine `data()` registration

Pagination is not registered via `Alpine.data()` as a standalone component. It's a composable — a factory function that each consumer calls within its own `x-data`:

```js
// In all-books.js:
import withPagination from './pagination';

export default function (initialData) {
    return {
        books: [],
        ...withPagination({
            paginationRoute: 'books.page',
            searchColumn: ['title'],
            searchTerm: '',
        }),
        // ... additional state and methods
    };
}
```

The consumer's `x-data` function must merge the pagination state at the top level so that the `_pagination.blade.php` partial (which references `page`, `getPage`, `isCurrentPage`) works correctly.

### Timing: `init()` collision

Both the pagination composable and the consumer component define `init()`. Only one `init()` runs per Alpine component — and it's the last one defined (spread order matters).

**Fix:** Spread pagination **first**, then define the consumer's `init()` **last**:

```js
// Right:
{
    ...withPagination({ paginationRoute: ... }),
    books: [],
    init() { ... },  // wins
}
```

Or, have the composable not define `init()` and call `getPage(1)` explicitly in the consumer's `init()`. The latter is safer and more explicit:

```js
export default function (config) {
    return {
        page: null,
        loaded: false,
        getPage(n) { ... },
        isCurrentPage(n) { ... },
    };
}
```

Consumer:
```js
export default function () {
    const pagination = withPagination({
        paginationRoute: 'books.page',
        searchColumn: ['title'],
    });
    return {
        ...pagination,
        books: [],
        init() {
            this.getPage(1);  // explicit
        },
    };
}
```

Prefer the **explicit** approach — no `init()` in the composable, consumer calls `getPage(1)` when ready.

### Blade partial inside `x-for`

The `_pagination.blade.php` partial is included on pages that may or may not have `#vue-root` active. It uses `x-show`, `x-for`, `x-on:click.prevent`, `x-bind:class`, and `x-text` — all Alpine directives safe inside or outside `#vue-root`.

When used on pages that still have `#vue-root` active, `x-for` on `<template>` is incompatible with Vue 2 (see Spec 005). The consumer page must set `useVueRoot => false`.

### Route parameters use singular resource names

- `books.page` → `{ page: n, searchColumn: [...], searchTerm: s, perPage: 10 }`
- `authors.page` → `{ page: n, searchColumn: [...], searchTerm: s, perPage: 10 }`

## Edge Cases

- **No results**: API returns `{ data: [], current_page: 1, last_page: 0, ... }` — nav hidden (`last_page > 1` is false)
- **Single page**: `last_page === 1` — nav hidden
- **Search with no matches**: `last_page === 0` — nav hidden, empty results shown
- **Network error**: Error logged to console, no UI change
- **Rapid page clicks**: Each click fires its own request; last-response-wins behavior is acceptable
