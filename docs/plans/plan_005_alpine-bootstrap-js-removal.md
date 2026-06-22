# Plan 005: Alpine.js Setup + Bootstrap JS Removal

**Goal:** Replace Bootstrap 4 JS (collapse, dropdown, tooltip) with Alpine.js. Remove jQuery, Popper, Lodash.

**Architecture:** Alpine co-exists with Vue 2 inside `#app`. Alpine starts after Vue mount. Bootstrap SCSS stays for Vue components.

**Tech Stack:** Alpine 3.14, @alpinejs/collapse 3.14, Laravel Vite

---

### Task 1: Update packages

**Files:**
- Modify: `package.json`

- [ ] Run npm install/uninstall
  ```bash
  npm uninstall jquery popper.js @popperjs/core lodash && npm install alpinejs@^3.14.0 @alpinejs/collapse@^3.14.0
  ```
  Expected: packages removed, new packages added, `npm run build` still works

### Task 2: Rewrite `app.js`

**Files:**
- Modify: `resources/js/app.js`

- [ ] Replace content:
  ```js
  import Vue from 'vue';
  import Alpine from 'alpinejs';
  import collapse from '@alpinejs/collapse';
  import '@fortawesome/fontawesome-free/js/all.js';

  Alpine.plugin(collapse);

  import axios from 'axios';
  window.axios = axios;
  window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  const token = document.head.querySelector('meta[name="csrf-token"]');
  if (token) {
      window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
  }

  Vue.mixin({
      methods: {
          route: route
      }
  });

  Vue.component('shelf-manager', () => import('./components/library/shelves/ShelfManager.vue'));
  Vue.component('shelf-form', () => import('./components/library/shelves/ShelfForm.vue'));
  Vue.component('shelf-books', () => import('./components/library/shelves/ShelfBooks.vue'));

  Vue.component('all-books', () => import('./components/library/books/AllBooks.vue'));
  Vue.component('book-card', () => import('./components/library/books/BookCard.vue'));
  Vue.component('book-form', () => import('./components/library/books/BookForm.vue'));

  Vue.component('all-authors', () => import('./components/library/authors/AllAuthors.vue'));
  Vue.component('author-card', () => import('./components/library/authors/AuthorCard.vue'));
  Vue.component('select-authors', () => import('./components/library/authors/SelectAuthors.vue'));
  Vue.component('author-form', () => import('./components/library/authors/AuthorForm.vue'));

  Vue.component('book-editions', () => import('./components/library/editions/Editions.vue'));
  Vue.component('edition-card', () => import('./components/library/editions/EditionCard.vue'));
  Vue.component('edition-row', () => import('./components/library/editions/EditionRow.vue'));
  Vue.component('shelve-edition', () => import('./components/library/editions/ShelveEdition.vue'));

  Vue.component('all-location-types', () => import('./components/library/locationtypes/LocationTypes.vue'));

  Vue.component('pagination-vue', () => import('./components/library/Pagination.vue'));

  const app = new Vue({
      el: '#app',
  });

  Alpine.start();
  ```

### Task 3: Delete `bootstrap.js`

**Files:**
- Delete: `resources/js/bootstrap.js`

- [ ] Delete the file
  ```bash
  Remove-Item resources/js/bootstrap.js
  ```

### Task 4: Update `layouts/app.blade.php`

**Files:**
- Modify: `resources/views/layouts/app.blade.php`

- [ ] Add `x-data="{ navOpen: false }"` to `<nav>` element (line 18):
  ```html
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" x-data="{ navOpen: false }">
  ```

- [ ] Replace navbar toggler button (line 23):
  Before:
  ```html
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
  ```
  After:
  ```html
  <button class="navbar-toggler" type="button" @click="navOpen = !navOpen" :aria-expanded="navOpen" aria-controls="navbarSupportedContent" aria-label="{{ __('Toggle navigation') }}">
  ```

- [ ] Replace collapsible nav container (line 27):
  Before:
  ```html
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
  ```
  After:
  ```html
  <div x-show="navOpen" x-collapse class="navbar-collapse">
  ```

- [ ] Wrap user dropdown `<li>` in its own Alpine scope (lines 46-62):
  Before:
  ```html
  <li class="nav-item dropdown">
      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          {{ Auth::user()->name }} <span class="caret"></span>
      </a>

      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
      </div>
  </li>
  ```
  After:
  ```html
  <li class="nav-item dropdown" x-data="{ userOpen: false }">
      <a class="nav-link dropdown-toggle" href="#" @click.prevent="userOpen = !userOpen" :aria-expanded="userOpen" v-pre>
          {{ Auth::user()->name }} <span class="caret"></span>
      </a>

      <div x-show="userOpen" @click.outside="userOpen = false" class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
      </div>
  </li>
  ```

### Task 5: Update `layouts/nav.blade.php`

**Files:**
- Modify: `resources/views/layouts/nav.blade.php`

Identical changes as Task 4. Apply to `nav.blade.php`.

- [ ] Add `x-data="{ navOpen: false }"` to `<nav>` element
- [ ] Replace toggler button `data-toggle` with `@click="navOpen = !navOpen"`
- [ ] Replace collapsible container: `x-show="navOpen" x-collapse`
- [ ] Wrap user `<li>` in `x-data="{ userOpen: false }"`, replace dropdown toggle + menu with Alpine

### Task 6: Update `editions/progress.blade.php`

**Files:**
- Modify: `resources/views/library/editions/progress.blade.php`

- [ ] Remove `data-toggle="tooltip"` and `data-placement="bottom"` from progress bar div (line 30-32):
  Before:
  ```html
  <div class="progress-bar"
          role="progressbar"
          data-toggle="tooltip"
          data-placement="bottom"
          title="@if($allProgress[$i][0] == $allProgress[$i][1]) {{ $allProgress[$i][0] }} @else {{ $allProgress[$i][0] }} to {{ $allProgress[$i][1] }} @endif">
  ```
  After:
  ```html
  <div class="progress-bar"
          role="progressbar"
          title="@if($allProgress[$i][0] == $allProgress[$i][1]) {{ $allProgress[$i][0] }} @else {{ $allProgress[$i][0] }} to {{ $allProgress[$i][1] }} @endif">
  ```

### Task 7: Update `editions/show.blade.php`

**Files:**
- Modify: `resources/views/library/editions/show.blade.php`

- [ ] Remove jQuery tooltip init script:
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

### Task 8: Build and verify

- [ ] Run Vite build
  ```bash
  npm run build
  ```
  Expected: success, no errors

- [ ] Run tests
  ```bash
  docker compose exec -w /var/www/html lib-dev ./vendor/bin/phpunit
  ```
  Expected: OK (2 tests, 2 assertions)

- [ ] Manual check: load home page, verify navbar collapse works, user dropdown works, progress bar tooltips show on hover, no console errors
