# Spec 033: View Replacement — Bootstrap to Component Library

**Status:** Approved

**References:** ADR-0020, Spec 018–032 (all component specs), ADR-0020 Phase 3

## Objective

Replace every Bootstrap class in every Blade view with the custom component library. One view group at a time, verifying visual parity and test suite passage after each.

## Scope

### In Scope

- All Blade views in `resources/views/`
- All Bootstrap CSS classes (`btn`, `card`, `form-control`, `badge`, `alert`, `nav`, `modal`, `dropdown`, `table`, `container`, `row`, `col-*`, `mt-*`, `mb-*`, `p-*`, etc.)
- Replacement with equivalent component library classes and Blade components
- Per-view verification (visual + test suite)

### Out of Scope

- SCSS component implementation (already spec'd in 018–031)
- JavaScript behavior changes (only CSS class replacements)
- Layout restructuring (keep same DOM hierarchy where possible)
- Performance optimization

## Migration Strategy

Replace views in dependency order (simplest, least shared → most complex, most shared):

### Phase 1: Auth Pages (no dependencies from other views)

1. `auth/login.blade.php`
2. `auth/register.blade.php`

**Bootstrap classes to replace:** container, card, card-header, card-body, form-control, btn, btn-primary, alert, invalid-feedback

**Component library equivalents:** Container, Card, Form Input, Button, Alert

### Phase 2: Layout Shell (shared by all pages)

3. `layouts/app.blade.php`

**Bootstrap classes to replace:** container, nav, navbar, navbar-nav, nav-item, nav-link, dropdown, dropdown-menu, dropdown-item, collapse

**Component library equivalents:** Container, Nav, Dropdown, Avatar

### Phase 3: Static Pages (simple content)

4. `home.blade.php` (dashboard)
5. `welcome.blade.php`

**Bootstrap classes to replace:** container, card, row, col-*, table, badge

**Component library equivalents:** Container, Card, Grid, Badge, Table

### Phase 4: CRUD List Pages (shared list patterns)

6. `library/authors/index.blade.php`
7. `library/books/index.blade.php`
8. `library/location-types/index.blade.php`

**Bootstrap classes to replace:** container, card, table, btn, btn-sm, btn-danger, badge, dropdown, pagination, form-inline, form-control, float-right

**Component library equivalents:** Container, Card, Table, Button, Badge, Dropdown, Pagination, Form Input

### Phase 5: CRUD Form Pages (shared form patterns)

9. `library/authors/form.blade.php`
10. `library/books/form.blade.php`
11. `library/location-types/form.blade.php`

**Bootstrap classes to replace:** container, card, card-header, card-body, card-footer, form-group, form-control, form-check, form-check-input, btn, btn-primary, alert, invalid-feedback, text-danger

**Component library equivalents:** Container, Card, Form Input (all types), Button, Alert

### Phase 6: Detail / Manager Views (most complex)

12. `library/books/show.blade.php`
13. `library/shelves/manager.blade.php` (on home page)
14. `library/shelves/_shelf.blade.php`

**Bootstrap classes to replace:** container, card, row, col-*, table, badge, btn, btn-sm, dropdown, tabs/navs

**Component library equivalents:** Container, Card, Grid, Table, Badge, Button, Dropdown, Tabs, Nav

## Per-View Procedure

For each view:

1. **Audit** — Find all Bootstrap classes with `rg 'class="[^"]*(btn|card|form-control|badge|alert|nav|modal|dropdown|table|container|row|col-|mt-|mb-|p-|text-|float-|d-|justify-content-|align-items-)[^"]*"' resources/views/<path>`
2. **Replace** — Map each Bootstrap class to component library equivalent:
   - `btn btn-primary` → `<x-button variant="primary">`
   - `btn btn-danger btn-sm` → `<x-button variant="danger" size="sm">`
   - `card` → `<x-card>`
   - `card-header` → `<x-slot:header>`
   - `card-body` → (slot content, no wrapper)
   - `card-footer` → `<x-slot:footer>`
   - `form-group` → (Form Input handles wrapping)
   - `form-control` → `<x-form-input>`
   - `badge badge-*` → `<x-badge variant="*">`
   - `alert alert-*` → `<x-alert variant="*">`
   - `container` → `<x-container>` or `.container`
   - `row col-*` → Grid primitives
   - `table` → `<x-table>`
   - `pagination` → `<x-pagination>`
   - `dropdown` → `<x-dropdown>`
   - `nav` → `<x-nav>`
   - `modal` → `<x-modal>`
3. **Verify** — Load page, confirm visual parity (same layout, spacing, colors)
4. **Test** — Run `php artisan test` (Pest) and `npx playwright test` (Playwright)
5. **Commit**

## Bootstrap Class → Library Mapping

| Bootstrap | Component Library |
|-----------|------------------|
| `btn btn-primary` | `<x-button variant="primary">` |
| `btn btn-secondary` | `<x-button variant="secondary">` |
| `btn btn-danger` | `<x-button variant="danger">` |
| `btn btn-sm` | `size="sm"` |
| `btn btn-lg` | `size="lg"` |
| `btn-block` | `class="btn--block"` |
| `card` | `<x-card>` |
| `card-header` | `<x-slot:header>` |
| `card-body` | (default slot) |
| `card-footer` | `<x-slot:footer>` |
| `form-group` | (omitted — Form Input handles layout) |
| `form-control` | `<x-form-input>` |
| `form-control-sm` | `size="sm"` |
| `form-check` | `<x-form-input type="checkbox">` or `type="radio"` |
| `form-check-input` | included in Form Input |
| `form-check-label` | included in Form Input |
| `badge` | `<x-badge>` |
| `badge-primary` | `variant="primary"` |
| `badge-secondary` | `variant="secondary"` |
| `badge-success` | `variant="success"` |
| `badge-danger` | `variant="danger"` |
| `badge-warning` | `variant="warning"` |
| `badge-info` | `variant="info"` |
| `alert` | `<x-alert>` |
| `alert-danger` | `variant="danger"` |
| `alert-success` | `variant="success"` |
| `alert-warning` | `variant="warning"` |
| `alert-info` | `variant="info"` |
| `alert-dismissible` | `dismissible` prop |
| `container` | `<x-container>` or `.container` class |
| `container-fluid` | `.container--fluid` |
| `row` | `.grid` |
| `col-*` | `.grid__col--{n}` |
| `col-md-6` | `.grid__col--md-6` |
| `table` | `<x-table>` |
| `table-striped` | `striped` prop |
| `table-hover` | `hover` prop |
| `table-bordered` | `bordered` prop |
| `table-sm` | `compact` prop |
| `dropdown` | `<x-dropdown>` |
| `dropdown-menu` | (menu is part of component) |
| `dropdown-item` | `<x-dropdown-link>` or `<x-dropdown-button>` |
| `dropdown-divider` | `.dropdown__divider` |
| `nav` | `<x-nav>` |
| `navbar` | `<x-nav>` |
| `nav-item` | (part of Nav's `$links` slot) |
| `nav-link active` | handled by `active` prop |
| `modal` | `<x-modal>` |
| `modal-dialog` | (part of component) |
| `modal-content` | (part of component) |
| `modal-header` | `title` prop |
| `modal-body` | (default slot) |
| `modal-footer` | `<x-slot:footer>` |
| `pagination` | `<x-pagination>` |
| `mt-*` / `mb-*` / `my-*` | primitive spacing `.mt-*` / `.mb-*` / `.my-*` |
| `p-*` / `px-*` / `py-*` | primitive spacing `.p-*` / `.px-*` / `.py-*` |
| `text-danger` | `.text--danger` (type primitive) |
| `text-muted` | `.text--muted` (type primitive) |
| `float-right` | `.flex--end` or `.ml-auto` |
| `d-flex` | `.flex--row` |
| `justify-content-between` | `.flex--space-between` |
| `align-items-center` | `.flex--center` |
| `collapse` | Alpine `x-show` |
| `show` | Alpine `x-show` state |

## Verification

After each view replacement:

1. **Visual inspection** — Load page in browser, check layout, spacing, colors, responsive behavior
2. **Pest tests** — `docker compose exec lib-dev php artisan test` — all 25 must pass
3. **Playwright tests** — `npx playwright test --config=tests/playwright.config.js` — all 15 must pass
4. **Bootstrap audit** — `rg 'class="[^"]*(btn|card|form-control|badge|alert|nav|modal|dropdown|table|container|row|col-)[^"]*"' resources/views/<path>` — must return no results for the replaced view

## Acceptance Criteria

1. All views in Phases 1–6 have zero Bootstrap classes
2. All 25 Pest tests pass after Phase 6
3. All 15 Playwright tests pass after Phase 6
4. Visual parity: each page looks equivalent to its Bootstrap version
5. No regression in responsive layout
6. Auth pages retain correct form validation display
7. Nav retains correct active state, responsive collapse, and user dropdown
8. CRUD pages retain correct CRUD operations
9. Shelf manager retains drag-and-drop functionality
10. `rg 'class="[^"]*(btn|card|form-control|badge|alert|nav|modal|dropdown|table|container|row|col-|mt-|mb-|p-|text-|float-|d-|justify-content-|align-items-)[^"]*"' resources/views/` returns zero results across all views after Phase 6
