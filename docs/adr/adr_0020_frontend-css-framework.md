# ADR-0020: Frontend CSS Framework Migration — Bootstrap to Custom SCSS

**Status:** Approved

**Date:** 2026-06-22

**Approved:** 2026-06-24

**Replaces:** None

**Supersedes:** None

## Context

The app's frontend currently uses Bootstrap 4 for layout, cards, forms, badges, buttons, and grid. Bootstrap JS was already removed (Spec-005), leaving only the CSS layer. The target stack (Vite + Alpine.js + SCSS) is in place, but the visual layer is still Bootstrap — the app looks generic and every custom design requires escalating CSS overrides.

The frontend has been identified as the remaining piece of the "frontend overhaul" referenced in ADR-0015. Removing Bootstrap completes the migration target set years ago.

## Decision Drivers

- Must support a custom, modern design identity (not generic Bootstrap look)
- Must integrate with existing Vite + Alpine.js build pipeline
- Must not introduce a new CSS framework dependency (no Tailwind, no UnoCSS, no DaisyUI)
- SCSS is already set up and the developer knows it — no framework to learn
- The project is a learning/practice vehicle — writing CSS by hand is the point
- Migration must be incremental — full rewrites are risky

## Considered Options

### A. Custom SCSS with Component Library (chosen)

Build a system of reusable SCSS partials and Blade components that cover every UI element in the app: containers, buttons, cards, forms, badges, typography, spacing, layout grid, icons, tables, navigation, modals. No utility framework, no component library dependency — everything is purpose-built for this app.

**Pros:**
- Zero new dependencies
- SCSS infrastructure already exists in the Vite pipeline
- Full design control — no framework opinions to fight
- Valuable CSS/systems-building skill development (the project is a learning vehicle)
- Smallest possible production CSS (only what you use)
- No purge/JIT configuration, no content-path maintenance

**Cons:**
- Must design and implement every component from scratch — significant up-front effort
- Must define, document, and maintain a design token system (spacing, color, type, breakpoints)
- No responsive utility shortcuts (write media queries manually or build a mixin system)
- No community component library to pull from
- Requires ongoing discipline to keep styles consistent across the codebase

### B. Tailwind CSS v4

Utility-first CSS framework, the Laravel ecosystem standard. First-party Vite plugin.

**Pros:**
- Largest ecosystem (DaisyUI, Flowbite, shadcn)
- JIT compiler produces <10KB production CSS
- Design tokens built in (spacing, type, color scales)
- Responsive prefixes (`md:`, `lg:`) eliminate manual media queries
- Consistent with Laravel community conventions

**Cons:**
- Adds a framework dependency with its own conventions
- Learning curve — utility class names must be memorized
- Verbose HTML without component extraction discipline
- CSS-first config in v4 (`@theme`) is a new mental model
- Developer actively does not want it

### C. UnoCSS

Atomic CSS engine, Tailwind-compatible preset. Near-instant builds, slightly smaller bundles than Tailwind.

**Pros:**
- ~50% smaller bundles than Tailwind
- ~1ms build times
- Vite-native

**Cons:**
- Same utility-first paradigm as Tailwind (developer doesn't want this)
- Smaller ecosystem
- Preset system adds conceptual overhead
- Fewer learning resources

### D. Native CSS with modern features (`@scope`, `@container`, `:has()`)

Browser-native approach using modern CSS. Zero build-time dependencies for styling (Vite used only for SCSS compilation).

**Pros:**
- Zero framework dependency
- `@scope` eliminates selector naming collisions
- `@container` enables responsive components
- Will outlive every framework

**Cons:**
- Still requires manual style authoring (same as Option A)
- Less mature browser support for `@scope` than SCSS nesting
- Fewer resources/templates compared to SCSS ecosystem
- SCSS already in use — switching to native CSS mid-project adds unnecessary tooling churn

### E. Hybrid: Bootstrap + custom SCSS during migration

Both coexist. Replace Bootstrap classes with custom SCSS component by component.

**Pros:**
- Lowest risk — rollback possible per component
- No feature freeze during migration
- Incremental, testable in production

**Cons:**
- CSS bundle includes Bootstrap during transition (large)
- Migration drags without clear completion criteria
- Must maintain both systems simultaneously

## Decision Outcome

**Chosen: A — Custom SCSS with a purpose-built Component Library.**

### Rationale

1. **No new framework.** The project's target stack was always Vite + Alpine.js + SCSS. Bootstrap was the only piece not yet replaced. Adding Tailwind (or anything else) would make this a three-framework migration instead of completing the two-framework target.

2. **Learning vehicle alignment.** Writing CSS and designing a component system by hand is the point. This project exists to practice and learn — outsourcing styling to a utility framework or importing a pre-built component library defeats that purpose. Building the component library ourselves forces deliberate decisions about design tokens, naming conventions, composability, and responsive patterns.

3. **SCSS infrastructure is ready.** `resources/css/app.scss`, `resources/css/library.scss`, and the Vite SCSS plugin are already configured and working. No setup cost.

4. **Bundle size.** Custom SCSS produces the smallest possible CSS — only what's actually used, with no framework overhead at all. Bootstrap 4 is ~150KB; even a comprehensive custom component library for this app should be well under 30KB.

5. **Full control.** No framework opinions to fight. If the design calls for a non-standard component, the SCSS writes exactly what's needed with no overrides.

6. **Component library is the right investment.** We need every UI element anyway. Building them as a documented, reusable library means each component is written once, used everywhere, and easy to change later. The alternative (ad-hoc CSS per page) leads to inconsistency bloat.

### Consequences

**Positive:**
- Zero new dependencies
- Smallest possible production CSS
- Full design ownership — no "looks like Bootstrap" or "looks like Tailwind"
- Component library becomes a single source of truth for every UI element
- Blade component layer enforces markup consistency across pages
- Design tokens prevent style drift without framework guardrails
- SCSS and design-systems skills develop directly (valuable skill set)
- Style guide provides onboarding docs and design boundaries for any future developer

**Negative:**
- Significant up-front effort to design and build the full component library
- Must define and maintain design tokens (spacing scale, color palette, type scale) or risk inconsistency
- Responsive design requires manual media queries (no `md:` prefix system)
- Every new UI element must be added to the library (can't reach for a framework component)
- New developers must learn the project's custom conventions rather than a standard framework
- Style guide must be kept in sync with actual components

**Neutral:**
- Existing Bootstrap styles can be migrated incrementally (Option E hybrid approach)
- Bootstrap's grid and spacing conventions can inform the custom system (don't need to reinvent everything)
- The component library can evolve: breaking changes are confined to the library boundary; consumers only need updated class names

## Design Principle: Portable SCSS Layer, Framework-Bound Presentation

The SCSS layer (tokens → primitives → components) is **framework-agnostic and portable** — it can be copied into any project regardless of backend or frontend framework. It depends on nothing beyond SCSS compilation. The Blade component layer is Laravel-specific but thin — it maps directly to SCSS classes with zero business logic.

This means:
- SCSS component design decisions are made for general use, not just this app
- "Out of Scope" in a component spec means "this is not a reasonable SCSS feature" or "we chose not to build it for the SCSS layer" — not "the app doesn't need it yet"
- Blade components are syntactic sugar over SCSS classes; all visual behavior lives in SCSS
- The library aims to be a standalone design system you could publish or reuse across projects

## Component Library Architecture

The library has five layers, each depending only on the layers below it:

```
                    ┌─────────────────────────────┐
                    │       Style Guide           │  (documentation)
                    │  (do/don't, usage rules,    │
                    │   patterns, rationale)       │
                    └───────────┬─────────────────┘
                    ┌───────────▼─────────────────┐
                    │    Blade Components          │  (templates)
                    │  (x-button, x-card,          │
                    │   x-form-input, x-badge)     │
                    └───────────┬─────────────────┘
                    ┌───────────▼─────────────────┐
                    │    SCSS Components           │  (styles)
                    │  (_button.scss, _card.scss,  │
                    │   _form.scss, _badge.scss)   │
                    └───────────┬─────────────────┘
                    ┌───────────▼─────────────────┐
                    │    SCSS Primitives           │  (composition)
                    │  (_grid.scss, _type.scss,    │
                    │   _spacing.scss, _flex.scss) │
                    └───────────┬─────────────────┘
                    ┌───────────▼─────────────────┐
                    │    Design Tokens             │  (foundation)
                    │  (_variables.scss: spacing,  │
                    │   colors, type, breakpoints) │
                    └─────────────────────────────┘
```

### Layer 1: Design Tokens (`resources/css/tokens/`)

Variables that define every measurable value in the system. No class selectors — only `$variable` definitions.

| Token Group     | File                          | Examples                                          |
|-----------------|-------------------------------|---------------------------------------------------|
| Spacing scale   | `_spacing.scss`               | `$space-xs: 4px; $space-sm: 8px; $space-md: 16px;` |
| Color palette   | `_colors.scss`                | `$color-primary: #...; $color-danger: #...;`       |
| Type scale      | `_typography.scss`            | `$font-size-xs: 0.75rem; $font-size-sm: 0.875rem;` |
| Breakpoints     | `_breakpoints.scss`           | `$bp-sm: 576px; $bp-md: 768px;`                    |
| Borders         | `_borders.scss`               | `$radius-sm: 4px; $radius-md: 8px;`                |
| Shadows         | `_shadows.scss`               | `$shadow-card: 0 2px 4px rgba(0,0,0,0.1);`         |
| Z-index         | `_zindex.scss`                | `$z-dropdown: 100; $z-modal: 200;`                 |
| Transitions     | `_transitions.scss`           | `$transition-fast: 150ms ease;`                    |

**Rules:**
- Every hard-coded value in a component partial must reference a token variable.
- Tokens never change meaning (e.g., `$color-primary` is always the brand color; its hex value can change without breaking consumers).

### Layer 2: SCSS Primitives (`resources/css/primitives/`)

Reusable class generators and mixins that compose tokens into structural patterns. Not components themselves — they are the building blocks components use.

| Primitive  | File         | What it provides                                             |
|------------|--------------|--------------------------------------------------------------|
| Container  | `_container.scss` | `.container` (max-width centered wrapper), `.container--fluid` |
| Grid       | `_grid.scss`     | `.grid` / `.grid__col` — CSS Grid layout, column span classes |
| Flex       | `_flex.scss`     | `.flex--row`, `.flex--col`, `.flex--center`, gap utility classes |
| Spacing    | `_spacing.scss`  | `.mt-*`, `.mb-*`, `.p-*` utility classes (generated from token map) |
| Type       | `_type.scss`     | `.heading--1` through `.heading--6`, `.body`, `.caption`, `.small` |
| Visually hidden | `_sr-only.scss` | `.sr-only` for accessible screen-reader-only content        |
| Responsive | `_responsive.scss` | `respond-to($bp)` mixin for media query generation       |

**Rules:**
- Primitives output utility/structural classes, not component styles.
- The responsive mixin (`respond-to($bp)`) is the single entry point for media queries — no raw `@media` in component files.

### Layer 3: SCSS Components (`resources/css/components/`)

One SCSS partial per component. Each partial assumes the design tokens and primitives are loaded. Component class names use BEM-like naming with a project prefix to avoid collisions.

| Component   | File             | BEM pattern                       |
|-------------|------------------|-----------------------------------|
| Button      | `_button.scss`   | `.btn`, `.btn--primary`, `.btn--sm` |
| Card        | `_card.scss`     | `.card`, `.card__header`, `.card__body` |
| Form input  | `_form.scss`     | `.input`, `.input--error`, `.input__label` |
| Badge       | `_badge.scss`    | `.badge`, `.badge--success`, `.badge--warning` |
| Table       | `_table.scss`    | `.table`, `.table--striped`, `.table__th` |
| Alert       | `_alert.scss`    | `.alert`, `.alert--error`, `.alert--success` |
| Modal       | `_modal.scss`    | `.modal`, `.modal__backdrop`, `.modal__content` |
| Nav         | `_nav.scss`      | `.nav`, `.nav__item`, `.nav__link--active` |
| Dropdown    | `_dropdown.scss` | `.dropdown`, `.dropdown__menu`, `.dropdown__item` |
| List        | `_list.scss`     | `.list`, `.list--inline`, `.list__item` |
| Tabs        | `_tabs.scss`     | `.tabs`, `.tabs__tab`, `.tabs__tab--active` |
| Pagination  | `_pagination.scss` | `.pagination`, `.pagination__item` |
| Avatar      | `_avatar.scss`   | `.avatar`, `.avatar--sm`, `.avatar--lg` |

**Future components:** breadcrumbs, tooltips, progress bars, file upload, date picker, autocomplete.

**Rules:**
- Each component file is standalone — imports only tokens and primitives.
- Components never import other components. Composition happens in Blade.
- Every component supports `--variant` and `--size` modifier classes where applicable.
- No nesting deeper than 3 levels in BEM chain.

### Layer 4: Blade Components (`resources/views/components/`)

One Blade component per SCSS component. Consistent naming: `x-button`, `x-card`, `x-form-input`, etc.

**File structure:**
```
resources/views/components/
├── button.blade.php
├── card.blade.php
├── form-input.blade.php
├── badge.blade.php
├── alert.blade.php
├── modal.blade.php
├── table.blade.php
├── nav.blade.php
├── dropdown.blade.php
├── list.blade.php
└── tabs.blade.php
```

**Rules:**
- Blade components map 1:1 with SCSS component partials.
- Props/slots control variants (`variant="primary"`, `size="sm"`), not manual class concatenation in view files.
- Default props provide sensible defaults (e.g., `variant="primary"` for buttons).

### Layer 5: Style Guide (`docs/style-guide/`)

A living document that defines how components should and should not be used. This is not generated from code — it's a written reference with design rationale, use cases, boundaries, and anti-patterns.

```
docs/style-guide/
├── README.md          # Principles, overview, how to use the guide
├── principles.md       # Design principles (consistency, accessibility, simplicity)
├── tokens.md           # Design token reference (colors, spacing, type)
├── primitives.md       # Container, grid, flex, spacing utilities
├── button.md           # Button component spec
├── card.md             # Card component spec
├── form-input.md       # Form input component spec
├── badge.md            # Badge component spec
├── table.md            # Table component spec
├── alert.md            # Alert component spec
├── modal.md            # Modal component spec
├── nav.md              # Navigation component spec
└── patterns.md         # Common UI patterns (forms with validation, CRUD layouts)
```

Each component spec contains:
- **Purpose:** what this component is for
- **Variants:** available modifiers (sizes, colors, states)
- **Do:** correct usage examples (what it looks like in Blade)
- **Don't:** incorrect usage examples (what to avoid)
- **Composition rules:** what can nest inside this component, what can it sit inside
- **Accessibility:** aria attributes, keyboard handling, focus management
- **Responsive behavior:** how it changes at breakpoints
- **Future considerations:** known gaps, planned variants

**Composition rules** are critical. They answer questions like:
- Can a button go inside a card? (Yes — card footer.)
- Can a card go inside a card? (Yes — nested card lists.)
- Can an alert go inside a modal? (Yes — form validation.)
- Can a badge go inside a button? (No — use an icon instead.)
- Can a table go inside a card body? (Yes — data display pattern.)
- Can a form input go inside a dropdown? (No — violates dropdown's dismiss-on-click behavior.)

## Migration Strategy

### Phase 1: Scaffold the System

1. **Design tokens** — Define spacing scale, color palette, type scale, breakpoints, border radii, shadows. Write to `resources/css/tokens/*.scss`. Every value is a variable.
2. **SCSS primitives** — Build container, grid, flex, spacing utilities, type classes, responsive mixin. Write to `resources/css/primitives/*.scss`.
3. **App entry point** — Update `resources/css/app.scss` to import tokens, then primitives, then components (placeholder directory). Bootstrap imports remain during migration.
4. **Style guide skeleton** — Create `docs/style-guide/README.md`, `principles.md`, `tokens.md`, `primitives.md`.

### Phase 2: Build the Component Library

Build components in dependency order (simplest first). For each:

1. Write the SCSS partial (`resources/css/components/_component-name.scss`) using tokens and primitives only.
2. Register it in the component index.
3. Write the Blade component (`resources/views/components/component-name.blade.php`).
4. Write the style guide spec (`docs/style-guide/component-name.md`) with do/don't, composition rules, accessibility notes.
5. Visually verify against the existing Bootstrap version.
6. Commit.

**Order of implementation:**
- Alert (simplest, fewest variants)
- Badge (label + color)
- Button (3 variants × 3 sizes)
- Form input (text input, select, textarea, checkbox, radio, validation states)
- Card (header, body, footer variants)
- List (ordered, unordered, inline)
- Nav (horizontal, vertical, active state)
- Table (header, rows, striped, sortable)
- Dropdown (toggle + menu)
- Modal (backdrop, content, close, focus trap)
- Tabs (tab list, tab panel, active)
- Pagination (prev, next, page links, disabled, active)

### Phase 3: Replace Views Page by Page

For each Blade view:
1. Replace Bootstrap classes with component library equivalents.
2. Replace raw HTML elements with Blade components where appropriate.
3. Update Playwright selectors if aria-labels changed.
4. Run full test suite.
5. Commit.

**Order (simplest → most complex):**
1. Auth pages (login, register) — few components, isolated
2. Layout shell (nav, header, container) — affects every page, quick wins
3. Dashboard / home
4. Location types CRUD — simple list + form
5. Authors CRUD — list + form
6. Books CRUD — list + form
7. Shelves — manager UI
8. Quote view / progress pages — if surfaced in nav
9. Editions table — most complex component composition

### Phase 4: Remove Bootstrap

1. Remove `bootstrap` and `bootstrap-scss` from `package.json` and `package-lock.json`.
2. Delete Bootstrap SCSS imports from all entry files.
3. Run full test suite (Pest + Playwright).
4. Verify no Bootstrap classes remain in any Blade file (`rg 'class="[^"]*(btn|card|form-control|badge|alert|nav|modal|dropdown|table|container|row|col-)[^"]*"'`).
5. Commit.

## Compliance

1. All new components and pages must use custom SCSS classes, not Bootstrap.
2. Bootstrap CSS imports must not be added to new SCSS files.
3. Migration PRs must not mix Bootstrap and custom classes in the same component (exceptions: grid system during transitional phase).
4. Design tokens (spacing, color, type) must use SCSS variables, not hard-coded values.
5. Every SCSS component must have a corresponding Blade component in `resources/views/components/`.
6. Every component must have a style guide entry in `docs/style-guide/` before it's used in a view.
7. No Blade view may import SCSS partials directly — all styles come through the component library or primitives.

## Revisit Trigger

If the manual CSS overhead significantly slows feature development (e.g., a single page requires 200+ lines of new SCSS), evaluate adopting a utility-first framework. This decision is specifically about the right tool for a learning project; if the project's purpose shifts to production speed, re-evaluate.

If the style guide falls out of sync with actual components (component changes without docs updates for three consecutive PRs), the documentation process is too heavy — simplify it or automate it.
