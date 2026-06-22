# Spec 005: Alpine.js Setup + Bootstrap JS Removal

## Objective

Replace Bootstrap 4 JavaScript behaviors (collapse, dropdown, tooltip) with Alpine.js and CSS equivalents. Remove jQuery, Popper.js, and Bootstrap JS as runtime dependencies. Keep Bootstrap 4 SCSS for Vue 2 components.

## Acceptance Criteria

1. Navbar collapse toggles with slide animation on both layouts
2. User dropdown toggles on click in both layouts
3. Progress bar tooltips show location range on hover
4. jQuery, Popper.js, Bootstrap JS removed from production bundle
5. Vue 2 components render correctly (books, authors, shelves, editions pages)
6. `npm run build` succeeds, no Vite errors
7. PHPUnit tests pass (2/2)
8. No console errors on page load (auth + library pages)

## Packages

| Action | Package | Reason |
|--------|---------|--------|
| Install | `alpinejs` ^3.14 | Core Alpine framework |
| Install | `@alpinejs/collapse` ^3.14 | Slide animation for navbar collapse |
| Remove | `jquery` ^3.2 | Only used by Bootstrap JS |
| Remove | `popper.js` ^1.12 | Bootstrap 4 dropdown dependency |
| Remove | `@popperjs/core` ^2.9 | Unused leftover |
| Remove | `lodash` ^4.17 | Not used in any Vue component or JS file |
| Keep | `bootstrap` ^4.1 | SCSS import needed for Vue 2 component styles |

## Files

### 1. `resources/js/app.js`

Replace Bootstrap import with Alpine. **Order matters: Alpine must start after Vue compiles** or it won't see `x-data` attributes inside `#app`.

```js
import Vue from 'vue';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import '@fortawesome/fontawesome-free/js/all.js';

Alpine.plugin(collapse);

// Keep Axios (moved from bootstrap.js — used by Vue components)
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Vue component registrations (unchanged) ...

const app = new Vue({
    el: '#app',
});

// Alpine after Vue — critical for coexisting inside #app
Alpine.start();
```

Alpine and Vue coexist on the same `#app` container because:
- Vue treats nodes without Vue directives as static HTML
- Alpine's `x-data`, `x-show`, `@click` etc. are unknown attributes to Vue and pass through
- `Alpine.start()` after Vue mount ensures Alpine sees the compiled DOM

### 2. `resources/js/bootstrap.js`

Delete the file. Its Axios + CSRF setup moves to `app.js`. jQuery/Popper/Bootstrap JS are deleted entirely.

### 3. `resources/views/layouts/app.blade.php`

**Navbar collapse button (line 23):**

Before:
```html
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" ...>
```

After:
```html
<button class="navbar-toggler" type="button" @click="navOpen = !navOpen" :aria-expanded="navOpen" ...>
```

Add `x-data="{ navOpen: false }"` to the `<nav>` element (line 18).

**Collapsible nav container (line 27):**

Before:
```html
<div class="collapse navbar-collapse" id="navbarSupportedContent">
```

After:
```html
<div x-show="navOpen" x-collapse class="navbar-collapse">
```

**User dropdown toggle (line 47):**

Before:
```html
<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" ...>
```

After:
```html
<a class="nav-link dropdown-toggle" href="#" @click.prevent="userOpen = !userOpen" :aria-expanded="userOpen" ...>
```

Add `userOpen: false` to the parent `x-data` or a nested scope.

**Dropdown menu (line 51):**

Before:
```html
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
```

After:
```html
<div x-show="userOpen" @click.outside="userOpen = false" class="dropdown-menu dropdown-menu-right">
```

Wrap the `<li>` in its own `x-data="{ userOpen: false }"` to keep state isolated.

### 4. `resources/views/layouts/nav.blade.php`

Identical changes as `app.blade.php` — navbar collapse button, collapsible container, user dropdown.

### 5. `resources/views/library/editions/show.blade.php`

Remove the jQuery tooltip init script:

```diff
 @section('body-scripts')
     @parent
-    <script>
-        $(function () {
-            $('[data-toggle="tooltip"]').tooltip()
-        })
-    </script>
 @endsection
```

### 6. `resources/views/library/editions/progress.blade.php`

Replace `data-toggle="tooltip"` with native browser `title` attribute — no JS needed. The `title` attribute is already present on line 32; just remove the `data-toggle` and `data-placement` attributes.

```diff
-<div class="progress-bar"
-        role="progressbar"
-        data-toggle="tooltip"
-        data-placement="bottom"
-        title="...">
+<div class="progress-bar"
+        role="progressbar"
+        title="...">
```

### 7. `resources/sass/_variables.scss`

No changes needed. Bootstrap SCSS variables remain for Vue component compatibility.

### 8. `resources/sass/library.scss`

No changes needed for this spec. Tooltips use native browser title.

## Order of Implementation

1. Install Alpine packages, uninstall jQuery/Popper/Bootstrap JS
2. Update `app.js` — add Alpine, remove bootstrap import
3. Delete `bootstrap.js`
4. Update `layouts/app.blade.php` — Alpine navbar collapse + dropdown
5. Update `layouts/nav.blade.php` — Alpine navbar collapse + dropdown
6. Update `editions/progress.blade.php` — remove `data-toggle`
7. Update `editions/show.blade.php` — remove jQuery tooltip script
8. Run `npm run build` to verify
9. Run `phpunit` to verify
10. Manual visual check on home, books, authors, editions pages

## Backout Plan

Revert changed files and run `npm install` with the old package.json. All changes are in version-controlled files.
