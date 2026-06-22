# Spec 016: Remove Vue 2 from Build Chain

**Status:** Implemented
**Applies to:** ADR-0002, ADR-0016, ADR-0017

## Goal

Remove Vue 2 entirely from the codebase after all Vue components have been migrated to Alpine.js. This is the final cleanup step — no more Vue dependencies, plugins, aliases, or layout infrastructure.

## Motivation

Every Vue component has now been migrated to Blade + Alpine. The last consumers were AllAuthors, AllBooks, AuthorCard, BookCard, and Pagination (Spec 014). Vue 2, `vue-template-compiler`, `@vitejs/plugin-vue2`, `vue-chartjs`, and the `#vue-root` layout mechanism were kept during incremental migration so both frameworks could coexist. With zero Vue components remaining, this scaffolding is dead code.

## Files

| File | Action |
|---|---|
| `package.json` | Modify — remove Vue-related packages |
| `vite.config.js` | Modify — remove vue2 plugin, remove `'vue'` alias |
| `resources/js/app.js` | Modify — delete Vue boot code, init Alpine directly |
| `resources/views/layouts/library.blade.php` | Modify — remove `#vue-root` + `$useVueRoot` conditional |
| `resources/views/layouts/app.blade.php` | Modify — remove `#vue-root` div |
| `resources/views/library/authors/index.blade.php` | Modify — remove `useVueRoot => false` |
| `resources/views/library/books/index.blade.php` | Modify — remove `useVueRoot => false` |
| `resources/views/library/books/create.blade.php` | Modify — remove `useVueRoot => false` |
| `resources/views/library/books/edit.blade.php` | Modify — remove `useVueRoot => false` |
| `resources/views/library/books/show.blade.php` | Modify — remove `useVueRoot => false` |
| `resources/views/library/locationtypes/index.blade.php` | Modify — remove `useVueRoot => false` |
| `resources/views/home.blade.php` | Modify — remove `useVueRoot => false` |
| `resources/js/components/` | Delete — entire tree, now empty |

## Implementation

### 1. Remove Vue from `package.json`

Remove from `devDependencies`:
- `@vitejs/plugin-vue2`
- `vue`
- `vue-template-compiler`

Remove from `dependencies`:
- `vue-chartjs`

`chart.js` is retained (it's a standalone library, not Vue-dependent).

### 2. Remove Vue from `vite.config.js`

- Delete `import vue2 from '@vitejs/plugin-vue2'`
- Remove `vue2()` from plugins array
- Remove `'vue': 'vue/dist/vue.esm.js'` from resolve aliases
- Keep `'~bootstrap'` alias (still needed for SCSS)

### 3. Strip Vue boot from `app.js`

Before:
```js
import Vue from 'vue';
// ...
Vue.mixin({ methods: { route } });
Vue.component('all-books', () => import('./...'));
Vue.component('book-card', () => import('./...'));
Vue.component('all-authors', () => import('./...'));
Vue.component('author-card', () => import('./...'));
Vue.component('pagination-vue', () => import('./...'));
const vueRoot = document.getElementById('vue-root');
if (vueRoot) {
    new Vue({ el: '#vue-root' });
}
```

After: All of the above removed. App.js becomes:
```js
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
// ... standard Alpine init
Alpine.start();
```

### 4. Remove `#vue-root` from layouts

**`library.blade.php`:** Replace the `$useVueRoot` conditional:
```blade
@if ($useVueRoot ?? true)
    <div id="vue-root">
        <main class="py-4">@yield('content')</main>
    </div>
@else
    <main class="py-4">@yield('content')</main>
@endif
```
With:
```blade
<main class="py-4">
    @yield('content')
</main>
```

**`app.blade.php`:** Remove `#vue-root` wrapper div around `<main>`.

### 5. Remove `useVueRoot => false` from all view files

Seven Blade views pass `['useVueRoot' => false]` to `@extends('layouts.library')`. Since `$useVueRoot` no longer exists in the layout, passing it has no effect. Revert to plain `@extends('layouts.library')`:
- `authors/index.blade.php`
- `books/index.blade.php`
- `books/create.blade.php`
- `books/edit.blade.php`
- `books/show.blade.php`
- `locationtypes/index.blade.php`
- `home.blade.php`

### 6. Delete empty component directories

`resources/js/components/library/authors/`, `resources/js/components/library/books/`, `resources/js/components/library/`, `resources/js/components/` — all empty after Spec 014 file deletions. Remove the tree.

## Acceptance Criteria

1. `npm install` succeeds (no missing peer dependencies)
2. `npm run build` succeeds (no Vue plugin errors)
3. `php vendor/bin/phpunit` — all tests pass
4. No `import Vue from 'vue'` anywhere in source
5. No `.vue` files anywhere in `resources/`
6. `#vue-root` does not appear in rendered page HTML for any route
7. All previously Alpine pages still render correctly (shelf form, shelf manager, author form, location types, book editions, book form, all authors, all books)
8. `resources/js/components/` directory no longer exists
9. `vue`, `vue-template-compiler`, `@vitejs/plugin-vue2` not in `package.json`

## Edge Cases

- **Views that never used `useVueRoot`**: Unaffected — they were already inside `#vue-root` (when `$useVueRoot` defaulted to `true`). With `#vue-root` gone, they're now directly in `#app`, which is the same DOM parent.
- **Pages with Alpine `@click` inside `#app` but outside `#vue-root`**: Already worked (Alpine is global). After removal, `@click` works everywhere because Vue's template compiler is no longer consuming it.
- **Old cached views**: Laravel's Blade cache in `storage/framework/views/` may contain stale compiled templates referencing `$useVueRoot`. Clear cache if needed.

## Rationale

Keeping dead framework scaffolding creates confusion:
- Future developers might think Vue is still in active use
- Extra bytes in the JS bundle (Vue runtime was ~1,200 kB before components)
- Extra DOM layer (`#vue-root`) complicates CSS selectors
- Build pipeline has unnecessary plugin overhead
