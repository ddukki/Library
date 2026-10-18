# Form & UI Audit — Mallard Library App

**Date:** 2026-06-26
**Codebase:** C:\Users\ddukki\code\Library

---

## 1. Complete File Inventory

### All Blade Views (59 files)

#### Layouts (3)
- layouts/app.blade.php — Base layout (auth pages)
- layouts/library.blade.php — Base layout (library pages)
- layouts/nav.blade.php — Navigation partial (included by library layout)

#### Auth Views (5) — extend layouts.app
- uth/login.blade.php
- uth/register.blade.php
- uth/verify.blade.php
- uth/passwords/email.blade.php
- uth/passwords/reset.blade.php

#### Books (5)
- library/books/create.blade.php — extends layouts.library
- library/books/edit.blade.php — extends layouts.library
- library/books/show.blade.php — extends layouts.library
- library/books/index.blade.php — extends layouts.library
- library/books/_book-card.blade.php — partial

#### Authors (6)
- library/authors/create.blade.php — extends layouts.library
- library/authors/edit.blade.php — extends layouts.library
- library/authors/index.blade.php — extends layouts.library
- library/authors/_form.blade.php — shared form partial
- library/authors/_author-card.blade.php — partial
- library/authors/_author-select-row.blade.php — partial (table row)
- library/authors/_selected-author-badge.blade.php — partial

#### Shelves (7)
- library/shelves/create.blade.php — extends layouts.library
- library/shelves/edit.blade.php — extends layouts.library
- library/shelves/show.blade.php — extends layouts.library
- library/shelves/_form.blade.php — shared form partial
- library/shelves/_shelf-manager.blade.php — grid of shelves (home page)
- library/shelves/_books.blade.php — shelf detail books grid
- library/shelves/_edition-card.blade.php — edition card in shelf

#### Editions (7)
- library/editions/show.blade.php — extends layouts.library
- library/editions/index.blade.php — extends layouts.library (EMPTY)
- library/editions/quotes.blade.php — partial (quotes section)
- library/editions/progress.blade.php — partial (progress tracker)
- library/editions/_editions.blade.php — partial (edition list + add form)
- library/editions/_row.blade.php — partial (single edition row)
- library/editions/_shelve.blade.php — partial (shelve badge UI)

#### Location Types (2)
- library/locationtypes/index.blade.php — extends layouts.library
- library/locationtypes/_list.blade.php — partial

#### Partials (1)
- library/partials/_pagination.blade.php — Alpine-driven pagination

#### Other (2)
- home.blade.php — extends layouts.library
- welcome.blade.php — standalone (Laravel default, NOT used in app)

---

## 2. Blade Components (21)

| Component | Props | CSS Classes | Purpose |
|-----------|-------|-------------|---------|
| x-alert | variant, dismissible, toast, position | lert, lert--{variant}, lert--toast, lert--toast-{pos} | Alert/notification banners |
| x-avatar | src, alt, initials, size, status | vatar, vatar--{size}, vatar__img, vatar__initials, vatar__status | User avatar with initials fallback |
| x-avatar-group | avatars, size, max, direction | vatar-group, vatar-group--left | Stacked avatars |
| x-badge | variant, size, clickable, togglable, active | adge, adge--{variant}, adge--{size}, adge--clickable, adge--togglable | Inline badges/tags |
| x-button | variant, size, href, block, active, disabled, type | tn, tn--{variant}, tn--{size}, tn--block, tn--disabled | Buttons (renders <button> or <a>) |
| x-card | image, imagePosition, clickable, compact, href | card, card--compact, card--clickable, card--has-image-{pos} | Card container with header/body/footer slots |
| x-dropdown | placement | dropdown, dropdown--open, dropdown__menu--{placement} | Alpine-powered dropdown |
| x-dropdown-button | disabled | dropdown__item, dropdown__item--disabled | Dropdown item (button) |
| x-dropdown-link | href, disabled | dropdown__item, dropdown__item--disabled | Dropdown item (link) |
| x-form-input | name, label, type, value, placeholder, required, disabled, error, help | orm-input, orm-input--required, orm-input--invalid, orm-input__field, orm-input__label, orm-input__error, orm-input__help | Form input (text, select, textarea, checkbox, radio) |
| x-list | variant, draggable, tag | list, list--ordered, list--divided, list--inline, list--draggable | List wrapper |
| x-list-item | handle | list__item, list__handle | List item |
| x-modal | name, size, title, show | modal-backdrop, modal, modal--{size}, modal__header, modal__title, modal__close, modal__body, modal__footer | Modal dialog (Alpine + x-trap) |
| x-nav | variant, orientation, sticky, brand, brandUrl | 
av, 
av--dark, 
av--vertical, 
av--sticky, 
av__brand, 
av__toggle, 
av__links, 
av__links--open, 
av__end | Navigation bar |
| x-nav-link | href, active, icon | 
av__link, 
av__link--active, 
av__link-icon | Nav link item |
| x-pagination | paginator, size | pagination, pagination--sm, pagination__item, pagination__link, pagination__link--active, pagination__link--prev/next/ellipsis/disabled | Server-side pagination (Laravel paginator) |
| x-tab | name, label, disabled, icon | 	ab, 	ab--active, 	ab--disabled, 	ab__icon | Tab button |
| x-tab-panel | name | 	ab-panel, 	ab-panel--enter{,-start,-end} | Tab content panel |
| x-tabs | variant, orientation, active | 	abs, 	abs--pills, 	abs--vertical, 	ab-panels | Tab container |
| x-table | striped, hover, compact, stickyHeader, empty | 	able, 	able--striped, 	able--hover, 	able--compact, 	able--sticky-header, 	able-wrapper, 	able__empty | Table wrapper |
| x-th-sortable | sortable, direction | 	able__th--sortable | Sortable table header |

### Missing Components (notable gaps)
- No <x-form> wrapper component
- No <x-label> component
- No <x-textarea> component (handled by x-form-input)
- No <x-select> component (handled by x-form-input)
- No <x-checkbox> / <x-radio> component (handled by x-form-input)
- No <x-tooltip> component
- No <x-toast> component (alert has toast mode)
- No <x-spinner> / <x-loading> component
- No <x-breadcrumb> component

---

## 3. Form Pages — Layout Structure

### 3.1 Book Create/Edit

**Files:** ooks/create.blade.php, ooks/edit.blade.php

**Structure:**
`
<div x-data="bookForm(...)">
  <div class="container">
    <div class="form-group">              ← Bootstrap class (custom .form-group exists)
      <x-card compact>
        <x-slot:header>Book Information</x-slot:header>
        <input class="form-input__field"> ← Custom component class (NOT <x-form-input>)
      </x-card>
    </div>
    <div class="form-group">
      <x-card compact>
        <x-slot:header>Book Author(s)</x-slot:header>
        <div class="input-group" style="margin-bottom: 1rem">  ← inline style
          <input class="form-input__field">
          <div class="input-group__append">
            <x-button variant="secondary">
        </div>
        <table class="table">             ← Custom table (no <x-table>)
        <x-slot:footer>
          @include('library.partials._pagination')
      </x-card>
    </div>
    <div class="form-group">
      <x-button variant="primary" size="lg">
    </div>
  </div>
</div>
`

**Issues:**
- Uses raw <input> with orm-input__field instead of <x-form-input>
- Uses <table class="table"> instead of <x-table>
- Uses <div class="form-group"> instead of mb-md utility class
- Inline style margin-bottom: 1rem on input-group
- No <form> tag — all Alpine.js driven (POST via JS)

### 3.2 Author Create/Edit

**Files:** uthors/create.blade.php, uthors/edit.blade.php → both include uthors/_form.blade.php

**Structure:**
`
<div style="margin-top: 1.5rem">          ← inline style
  <div x-data="authorForm(...)" class="container">
    <x-card compact>
      <x-slot:header>New Author</x-slot:header>
      <div style="margin-bottom: 0.5rem"> ← inline style for label
      <div class="form-row">
        <div class="form-group">
          <label class="visually-hidden"> ← WRONG CLASS (should be sr-only)
          <input class="form-input__field">
        </div>
        ... (3x: first, middle, last name)
      </div>
      <div class="form-group">
        <x-button>
      </div>
    </x-card>
  </div>
</div>
`

**Issues:**
- Uses class="visually-hidden" — this class does NOT exist in the SCSS. Only .sr-only is defined.
- Uses class="form-group" and class="form-row" (custom, defined in _form.scss)
- Inline style for the "Name" label text instead of using a <label> or styled element
- No <x-form-input> usage
- Header always says "New Author" even on edit

### 3.3 Shelf Create/Edit

**Files:** shelves/create.blade.php, shelves/edit.blade.php → both include shelves/_form.blade.php

**Structure:**
`
<div class="container" style="margin-top: 1.5rem">  ← inline style
  <div x-data="shelfForm(...)">
    <div class="form-group">
      <label for="shelf_name">Shelf Name</label>
      <input class="form-input__field">
    </div>
    <x-button x-on:click="addShelf">
      + Add
    </x-button>
    <x-button x-on:click="updateShelf">
      + Update
    </x-button>
  </div>
</div>
`

**Issues:**
- Uses class="form-group" and raw <label> + <input> instead of <x-form-input>
- Inline style for top margin
- No <form> tag (Alpine driven)
- Simplest form — no card wrapper

### 3.4 Location Types (inline CRUD)

**Files:** locationtypes/index.blade.php → locationtypes/_list.blade.php

**Structure:**
`
<div class="container" style="margin-top: 1.5rem">   ← inline style
  <div class="flex flex--center" style="flex-direction: column">  ← inline style overrides flex--center
    <div x-data="locationTypes">
      <table class="table">                           ← not <x-table>
      <div class="input-group">
        <input class="form-input__field">
        <div class="input-group__append">
          <x-button>
        </div>
      </div>
    </div>
  </div>
</div>
`

**Issues:**
- Uses raw <table> instead of <x-table>
- Uses raw <button class="btn btn--danger btn--sm"> instead of <x-button variant="danger" size="sm">
- lex-direction: column inline style conflicts with lex--center (which sets center alignment)

### 3.5 Edition Add Form (inline in _editions.blade.php)

**Structure:**
`
<div x-show="showAddForm" style="margin-top: 1rem">
  <div class="form-group">
    <label for="name">Edition Name</label>
    <input class="form-input__field">
  </div>
  <div class="form-group">
    <label for="locationTypes">Edition Type</label>
    <select class="form-input__field">
  </div>
  <div class="form-group">
    <label for="size">Edition Size</label>
    <input class="form-input__field">
  </div>
  <x-button>
</div>
`

**Issues:**
- Uses raw <label> + <input>/<select> instead of <x-form-input>
- Inline style for margin

### 3.6 Quotes Form (inline in editions/quotes.blade.php)

**Structure:**
`
<form action="{{ route('quotes.store') }}" method="POST">  ← traditional form post
  <div class="form-group">
    <label for="quoteText">Quote Text</label>
    <textarea class="form-input__field">
  </div>
  <div class="form-group">
    <label for="location">Location</label>
    <input class="form-input__field">
  </div>
  <x-button type="submit">
</form>
`

**Issues:**
- Uses raw <label> + <textarea>/<input> instead of <x-form-input>
- This is the ONLY traditional form POST (not Alpine driven)

### 3.7 Progress Form (inline in editions/progress.blade.php)

**Structure:**
`
<form method="post" action="{{route('progress.store')}}">
  <div class="form-group" style="margin-top: 1rem">
    <label for="location_start">Reading Start</label>
    <input class="form-input__field">
  </div>
  <div class="form-group">
    <label for="location_end">Reading End</label>
    <input class="form-input__field">
  </div>
  <x-button type="submit">
</form>
`

**Issues:**
- Uses raw <label> + <input> instead of <x-form-input>
- Traditional form POST

### 3.8 Auth Forms (login, register, reset, email)

**Structure (all consistent):**
`
<div class="container container--sm" style="margin-top: 3rem">
  <x-card>
    <x-slot:header>{{ __('Login') }}</x-slot:header>
    <form method="POST" action="...">
      <x-form-input name="email" type="email" label="..." />
      <x-form-input name="password" type="password" label="..." />
      <x-form-input name="remember" type="checkbox" label="..." />
      <div class="flex flex--gap-sm" style="margin-top: 1rem">
        <x-button type="submit">Login</x-button>
      </div>
    </form>
  </x-card>
</div>
`

**Status:** These are the BEST implemented forms. They use <x-form-input> and <x-button> consistently.

---

## 4. Layout Patterns

### Container System
- .container — max-width 1200px, auto margins, $space-lg (24px) horizontal padding
- .container--sm — max-width 640px (used by auth pages)
- .container--md — max-width 768px
- .container--lg — max-width 1024px
- .container--fluid — no max-width

### Spacing Tokens
| Token | Value |
|-------|-------|
| $space-xs | 4px |
| $space-sm | 8px |
| $space-md | 16px |
| $space-lg | 24px |
| $space-xl | 32px |
| $space-2xl | 48px |

### Layout Utilities Available but Underused
| Utility | CSS | Used in views? |
|---------|-----|----------------|
| .flex | display: flex | Yes |
| .flex--center | display: flex; align-items: center; justify-content: center | Yes |
| .flex--gap-sm | gap: 8px | Yes (login) |
| .flex--gap-md | gap: 16px | No |
| .flex--1 | lex: 1 | No (inline style="flex: 1" used instead) |
| .mb-md | margin-bottom: 16px | Yes (home, 2x) |
| .mt-lg / .mt-md | margins | No |
| .text-center | 	ext-align: center | No (inline style used instead) |
| .border-bottom | border | Yes (editions) |
| .d-block | display: block | Yes (_shelf-manager) |
| .d-flex | display: flex | No (tabs only) |
| .grid | 12-column grid | No (inline CSS grid used instead) |
| .grid__col--{n} | column spans | No |

### Grid System
- Custom 12-column grid defined (grid + grid__col--{n} + responsive variants)
- **NOT used anywhere** — all grids are inline display: grid; grid-template-columns: ...

---

## 5. What's Outdated / Needs Cleanup

### 5.1 Inline Styles (95 occurrences across 35 files)

**Worst offenders:**
| File | Count | What's inlined |
|------|-------|----------------|
| ditions/progress.blade.php | 10 | grid layout, margins, flex, dynamic widths |
| ditions/_editions.blade.php | 8 | grid columns, font-weight, margins |
| shelves/_shelf-manager.blade.php | 8 | grid, text-align, positioning |
| home.blade.php | 6 | flex layout, margins |
| shelves/_books.blade.php | 6 | grid, text-align, margins |
| ditions/quotes.blade.php | 5 | padding, border, margins |
| uthors/_author-card.blade.php | 5 | flex, overflow, margins |

**Common inline patterns that should be utility classes:**
- style="margin-top: 1.5rem" → should be .mt-lg (24px ~= 1.5rem)
- style="margin-bottom: 1rem" → should be .mb-md (16px ~= 1rem)
- style="text-align: center" → should be .text-center
- style="flex: 1" → should be .flex--1
- style="display: grid; grid-template-columns: ..." → should use .grid system or new CSS classes
- style="align-items: center; justify-content: space-between" → should be .flex--center-between
- style="padding-top: 1.5rem; padding-bottom: 1.5rem" → should be .py-lg

### 5.2 Bootstrap Remnants

| Issue | Location | Fix |
|-------|----------|-----|
| class="visually-hidden" | uthors/_form.blade.php (3x) | Does not exist. Use class="sr-only" (defined in _sr-only.scss) |
| class="form-group" | 12+ locations | Custom class exists (defined in _form.scss), but inconsistent — some pages use it, some don't |
| class="form-row" | uthors/_form.blade.php | Custom class exists (defined in _form.scss), works correctly |
| class="form-input__field" on raw inputs | 15+ locations | Should use <x-form-input> component instead |
| class="btn btn--danger btn--sm" | locationtypes/_list.blade.php | Should use <x-button variant="danger" size="sm"> |
| class="btn btn--primary btn--sm" | ditions/_row.blade.php (2x) | Should use <x-button> |
| class="btn btn--secondary btn--sm" | ditions/_row.blade.php | Should use <x-button> |
| class="btn btn--outline-primary btn--sm" | uthors/_author-select-row.blade.php | Should use <x-button> |
| class="d-block" | shelves/_shelf-manager.blade.php | Bootstrap utility that exists in custom system too |
| class="custom-select custom-select--sm" | ditions/_shelve.blade.php | Custom class exists in _form.scss |

### 5.3 Raw HTML vs. Components

| Pattern | Where | Should Be |
|---------|-------|-----------|
| <input class="form-input__field"> | books create/edit, shelf form, edition form, progress form, quotes form | <x-form-input> |
| <textarea class="form-input__field"> | quotes form, progress form | <x-form-input type="textarea"> |
| <select class="form-input__field"> | edition form, shelve form | <x-form-input type="select"> |
| <table class="table"> | books create/edit, location types | <x-table> |
| <button class="btn btn--*"> | location types, edition rows | <x-button> |
| <div class="form-group"> | 12+ locations | Could be replaced with class="mb-md" |

### 5.4 Missing Component Usage

The <x-form-input> component is well-built with:
- Label rendering
- Error handling (integrates with Laravel $errors)
- Help text
- Required indicator
- Accessible aria attributes
- Checkbox/radio support

But it's **only used in auth views** (login, register, reset, email). All library forms use raw HTML inputs.

### 5.5 _variables.scss — Dead File

_variables.scss contains old Bootstrap-era variables ($blue, $indigo, $purple, etc.) that are NOT imported by pp.scss. The app uses 	okens/_colors.scss instead. This file should be deleted.

### 5.6 Two Layout Files

- layouts/app.blade.php — uses <x-nav> component directly
- layouts/library.blade.php — uses @include('layouts.nav') which then uses <x-nav>

Both are nearly identical. The only differences:
- pp.blade.php uses @vite directly in <head>
- library.blade.php wraps @vite in a @section('header-styles') block
- library.blade.php uses @include('layouts.nav') instead of inline <x-nav>

### 5.7 CSS Grid Not Used From Design System

The app has a full 12-column grid system defined (grid, grid__col--{n}, responsive variants) but **zero views use it**. All grid layouts are inline CSS display: grid; grid-template-columns: ....

### 5.8 Font Awesome Dependency

All views use <i class="fas fa-*"> for icons. This is Font Awesome 5/6 Free. The package.json has @fortawesome/fontawesome-free v7.2.0, which may have different class prefixes.

---

## 6. Summary of Findings

### Strengths
- Well-defined design system with tokens, primitives, and component SCSS
- 21 Blade components with consistent BEM naming
- <x-form-input> component is feature-rich (validation, help text, accessibility)
- Custom component library is complete (cards, buttons, modals, tabs, dropdowns, etc.)
- Alpine.js integration is clean and consistent
- Auth forms are exemplary — use components properly

### Weaknesses
1. **95 inline styles** across 35 files — most should be utility classes or CSS classes
2. **Components exist but aren't used** — <x-form-input>, <x-table>, <x-button> bypassed in library views
3. **Raw HTML class names mixed with component class names** — tn btn--primary used directly instead of <x-button>
4. **isually-hidden class doesn't exist** — must be sr-only
5. **Dead _variables.scss** with Bootstrap-era color variables
6. **Two near-identical layout files** that should be consolidated
7. **Grid system defined but unused** — all grids are inline CSS
8. **No form wrapper component** — each form reinvents its own wrapper pattern
9. **Inconsistent form patterns** — some use <x-card compact>, some don't; some use orm-group, some don't
10. **Header text hardcoded** — author form always says "New Author" even in edit mode

### Recommended Next Steps
1. Replace all raw <input> with <x-form-input> in library forms
2. Replace all raw <button class="btn"> with <x-button>
3. Replace all raw <table class="table"> with <x-table>
4. Replace inline styles with utility classes (mb-md, mt-lg, 	ext-center, lex--1, etc.)
5. Fix isually-hidden → sr-only in author form
6. Delete _variables.scss
7. Consolidate layouts/app.blade.php and layouts/library.blade.php
8. Create a form wrapper component or adopt consistent pattern
9. Add a orm-row → grid mapping to replace inline grids
