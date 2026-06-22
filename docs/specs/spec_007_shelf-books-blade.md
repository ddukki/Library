# Spec 007: ShelfBooks → Blade (Read-Only Display)

**Status:** Approved (implemented)
**Applies to:** ADR-0002, ADR-0004, ADR-0016

## Goal

Migrate `ShelfBooks.vue` to Blade partials. Purely presentational — no Alpine needed. Loop element extracted into its own partial for modularity/future Alpine. Also delete `EditionCard.vue` (dead code).

## Files

| File | Action |
|---|---|
| `resources/views/library/shelves/_books.blade.php` | **Create** — outer container partial |
| `resources/views/library/shelves/_edition-card.blade.php` | **Create** — per-edition card partial |
| `resources/views/library/shelves/show.blade.php` | Modify — replace `<shelf-books>` with `@include` |
| `resources/js/app.js` | Modify — remove Vue registrations |
| `resources/js/components/library/shelves/ShelfBooks.vue` | Delete |
| `resources/js/components/library/editions/EditionCard.vue` | Delete |

## Behavior

Same as current Vue component:
- Shelf name heading
- "Edit Shelf" link to edit page
- "Add New Book" link to books index
- List of editions, each showing: book title, author names, "X Edition" label
- Author format: `first_name middle_name last_name` (null parts filtered), comma-separated between authors

## Implementation

### 1. Create `_edition-card.blade.php`

Loop leaf — one card per edition. Ready for future Alpine enrichment.

```blade
<div class="col-12 mb-3">
    <a class="card" href="{{ route('editions.show', ['edition' => $edition->id]) }}">
        <div class="card text-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">{{ $edition->book->title }}</div>
                    <div class="col-12 small">
                        {{ $edition->book->authors->map(fn($a) => collect([$a->first_name, $a->middle_name, $a->last_name])->filter()->join(' '))->join(', ') }}
                    </div>
                    <div class="col-12">
                        <h1 class="mt-3"><i class="fas fa-book"></i></h1>
                    </div>
                    <div class="col-12 small">{{ $shelf->name }} Edition</div>
                </div>
            </div>
        </div>
    </a>
</div>
```

### 2. Create `_books.blade.php`

Outer container — heading, action links, and loop over editions.

```blade
<div class="container-fluid">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2>{{ $shelf->name }}</h2>
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('shelves.edit', ['shelf' => $shelf->id]) }}">
                        <i class="fas fa-edit"></i> Edit Shelf
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center mb-3">
            <a class="card" href="{{ route('books.index') }}">
                <div class="card">
                    <div class="card-body">
                        <p>Add New Book</p>
                        <h1 class="mt-3"><i class="fas fa-plus"></i></h1>
                    </div>
                </div>
            </a>
        </div>
        @foreach ($shelf->editions as $edition)
            @include('library.shelves._edition-card', ['edition' => $edition, 'shelf' => $shelf])
        @endforeach
    </div>
</div>
```

### 3. Update `show.blade.php`

```blade
@extends('layouts.library')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('library.shelves._books')
        </div>
    </div>
@endsection
```

### 4. Update `app.js`

Remove:
```js
Vue.component('shelf-books', () => import('./components/library/shelves/ShelfBooks.vue'));
Vue.component('edition-card', () => import('./components/library/editions/EditionCard.vue'));
```

### 5. Clean up dependent imports

`Editions.vue` imported `EditionCard.vue` (dead component, never used in template):

```diff
- import EditionCard from './EditionCard.vue'
  export default {
-     components: { EditionCard },
      props: ['book'],
```

### 6. Delete Vue files

- `resources/js/components/library/shelves/ShelfBooks.vue`
- `resources/js/components/library/editions/EditionCard.vue`

## Acceptance Criteria

1. `npm run build` succeeds
2. `php vendor/bin/phpunit` passes
3. Shelf show page renders: heading, edit link, add book link, edition list
4. Author names display correctly (null parts filtered, comma-separated)
5. Edition label shows as "X Edition"
6. No console errors
7. `shelf-books` and `edition-card` no longer in JS bundle

## Implementation Notes

### Resource Route Parameter Names

Laravel resource routes use **singular** parameter names derived from the resource name, not `{id}`:
- `Route::resource('shelves', ...)` → implicit `{shelf}`, not `{id}`
- `Route::resource('editions', ...)` → implicit `{edition}`, not `{id}`

Route helpers must match:
```blade
{{ route('shelves.edit', ['shelf' => $shelf->id]) }}   <!-- not ['id' => $shelf->id] -->
{{ route('editions.show', ['edition' => $edition->id]) }} <!-- not ['id' => $edition->id] -->
```

This is a common gotcha when migrating from explicit route definitions (which often use `{id}`) to resource routes.

## Edge Cases

- **Shelf with no editions**: Heading and links render, no edition cards
- **Edition with no authors**: Empty author string (no crash)
- **Edition with null book**: Would crash on `$edition->book->title` — same as current Vue (assumes eager loading is correct)
