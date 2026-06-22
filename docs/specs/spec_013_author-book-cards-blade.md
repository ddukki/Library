# Spec 013: AuthorCard + BookCard → Alpine-Compatible Blade Partials

**Status:** Implemented
**Applies to:** ADR-0016 (Blade partial + Alpine JS module pattern)

## Goal

Migrate two leaf-level Vue 2 display components to Blade partials designed for Alpine `x-for` iteration. Both are pure presentational — no state, no API calls, no interactivity.

**Architecture:** Each partial receives a `$item` parameter — a string naming the Alpine `x-for` loop variable. The partial outputs Alpine directives referencing that variable. No `Alpine.data()` registration needed.

## Convention: `$item` parameter

These partials are authored for Alpine `x-for` loops. The included partial receives `$item` as a string (the Alpine iteration variable name), and uses `{{ $item }}` to produce Alpine expressions:

```blade
{{-- Parent passes the Alpine loop variable name as a string --}}
<template x-for="(author, index) in authors" :key="index">
    @include('library.authors._author-card', ['item' => 'author'])
</template>

{{-- Inside _author-card.blade.php, {{ $item }} outputs "author" --}}
x-text="`${ {{ $item }}.first_name }`"
→ x-text="`${author.first_name}`"
```

Blade processes `{{ $item }}` at compile time, outputting the JS variable name. Alpine then evaluates the expression at runtime per iteration.

## Files

| File | Action |
|---|---|
| `resources/views/library/authors/_author-card.blade.php` | **Create** |
| `resources/views/library/books/_book-card.blade.php` | **Create** |
| `resources/js/app.js` | Modify — remove 2 Vue registrations |
| `resources/js/components/library/authors/AuthorCard.vue` | Delete |
| `resources/js/components/library/books/BookCard.vue` | Delete |

## Behavior

### AuthorCard

- Renders inside Alpine `x-for` iteration
- Displays: `first_name middle_name last_name` (space-separated)
- If author has books: shows "Books:" label + badge for each book title
- Edit icon linking to `authors.edit`
- No interactivity

### BookCard

- Renders inside Alpine `x-for` iteration
- Book title linked to `books.show`
- Authors displayed as: `firstName middleName lastName`, comma-separated between authors
- Null middle_name parts filtered out
- Edit icon linking to `books.edit`
- No interactivity

## Implementation

### 1. Create `_author-card.blade.php`

```blade
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    <span x-text="`${ {{ $item }}.first_name } ${ {{ $item }}.middle_name } ${ {{ $item }}.last_name }`"></span>
                    <br/>
                    <p class="small mb-0 mt-3" x-show="{{ $item }}.books && {{ $item }}.books.length > 0">
                        Books:
                    </p>
                    <template x-for="book in ({{ $item }}.books || [])" :key="book.id">
                        <span class="badge badge-primary mr-1" x-text="book.title"></span>
                    </template>
                </div>
                <div class="col-2">
                    <a x-bind:href="route('authors.edit', { author: {{ $item }}.id })">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
```

Rendered output when `item=author`:
```html
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    <span x-text="`${author.first_name} ${author.middle_name} ${author.last_name}`"></span>
                    <br/>
                    <p class="small mb-0 mt-3" x-show="author.books && author.books.length > 0">
                        Books:
                    </p>
                    <template x-for="book in (author.books || [])" :key="book.id">
                        <span class="badge badge-primary mr-1" x-text="book.title"></span>
                    </template>
                </div>
                <div class="col-2">
                    <a x-bind:href="route('authors.edit', { author: author.id })">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 2. Create `_book-card.blade.php`

```blade
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    <a x-bind:href="route('books.show', { book: {{ $item }}.id })"
                       x-text="{{ $item }}.title"></a>
                    <br/>
                    <p class="small mb-0 mt-3" x-show="{{ $item }}.authors && {{ $item }}.authors.length > 0">
                        By:
                    </p>
                    <template x-for="(author, n) in ({{ $item }}.authors || [])" :key="author.id">
                        <span class="small"
                              x-text="n < {{ $item }}.authors.length - 1
                                  ? `${author.first_name} ${author.middle_name} ${author.last_name},`
                                  : `${author.first_name} ${author.middle_name} ${author.last_name}`">
                        </span>
                    </template>
                </div>
                <div class="col-2">
                    <a x-bind:href="route('books.edit', { book: {{ $item }}.id })">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
```

Rendered output when `item=book`:
```html
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    <a x-bind:href="route('books.show', { book: book.id })"
                       x-text="book.title"></a>
                    <br/>
                    <p class="small mb-0 mt-3" x-show="book.authors && book.authors.length > 0">
                        By:
                    </p>
                    <template x-for="(author, n) in (book.authors || [])" :key="author.id">
                        <span class="small"
                              x-text="n < book.authors.length - 1
                                  ? `${author.first_name} ${author.middle_name} ${author.last_name},`
                                  : `${author.first_name} ${author.middle_name} ${author.last_name}`">
                        </span>
                    </template>
                </div>
                <div class="col-2">
                    <a x-bind:href="route('books.edit', { book: book.id })">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 3. Update `app.js` (deferred to Spec 014)

**Cannot remove Vue registrations yet.** The parent components `AllBooks.vue` and `AllAuthors.vue` still import `BookCard.vue` and `AuthorCard.vue` respectively. Removing the registrations or Vue files will break the build.

Deferred actions (to be done in Spec 014 when parent Vue components are deleted):
- Remove from `app.js`:
  ```js
  Vue.component('author-card', () => import('./components/library/authors/AuthorCard.vue'));
  Vue.component('book-card', () => import('./components/library/books/BookCard.vue'));
  ```
- Delete `resources/js/components/library/authors/AuthorCard.vue`
- Delete `resources/js/components/library/books/BookCard.vue`

## Acceptance Criteria

1. `npm run build` succeeds
2. `php vendor/bin/phpunit` passes
3. Blade partials render Alpine directives compatible with `$item` convention
4. _author-card.blade.php exists with correct `{{ $item }}.first_name` / `.middle_name` / `.last_name` references
5. _book-card.blade.php exists with correct `{{ $item }}.title` / `.authors` references
6. Vue files still present (parent components depend on them)
7. `author-card` and `book-card` remain in JS bundle until Spec 014

## Implementation Notes

### `$item` convention

The `$item` parameter is a Blade string passed to `@include`. It names the Alpine loop variable. The partial uses `{{ $item }}` (Blade output) to generate Alpine expressions referencing that variable.

```blade
{{-- Pass the Alpine loop variable name --}}
@include('library.authors._author-card', ['item' => 'author'])
@include('library.books._book-card', ['item' => 'book'])
```

Inside the partial, `{{ $item }}` outputs the literal string `author` or `book`, which Alpine evaluates as a JS expression.

### Why not PHP `$author`?

Blade `@include` is compile-time. If the partial used `$author->name`, it would need PHP variable `$author` to exist in scope — but inside `x-for`, there is no PHP `$author`, only an Alpine JS variable. The `$item` convention bridges this gap by letting Blade output a JS variable name.

### No Alpine state needed

These cards are pure markup with Alpine directives. They receive no Alpine state, dispatch no events, and register no `Alpine.data()`.

### Resource Route Parameter Names

```js
route('authors.edit', { author: {{ $item }}.id })   // not { id: ... }
route('books.show', { book: {{ $item }}.id })        // not { id: ... }
route('books.edit', { book: {{ $item }}.id })        // not { id: ... }
```

### Author name format matches existing

The Vue `AuthorCard.vue` rendered `author.first_name author.middle_name author.last_name` (space-separated, no null filtering). The Alpine version uses the same format — null `middle_name` renders as a double space, matching original behavior.

## Edge Cases

- **Author with no books**: `x-show` hides "Books:" label, no badges rendered
- **Author with null middle_name**: Renders as extra space (matching original Vue behavior)
- **Book with no authors**: `x-show` hides "By:" label
- **Book with null author middle_name**: Produces "firstName lastName" (Alpine string concat omits null)
