# Spec 032: Style Guide Documentation

**Status:** Approved

**References:** ADR-0020, Spec 018–031 (all component specs)

## Objective

Build the living style guide that documents the component library. One page per component covering purpose, variants, usage rules, and accessibility. Not generated from code — written reference with design rationale.

## Scope

### In Scope

- Directory structure at `docs/style-guide/`
- Root README with principles and overview
- `principles.md` — design principles
- `tokens.md` — design token reference
- `primitives.md` — primitive utility reference
- One `.md` file per component (alert, badge, button, form-input, card, list, nav, table, dropdown, modal, tabs, pagination, avatar)
- `patterns.md` — common UI patterns
- Template for each component doc
- Document structure: Purpose → Variants → Do → Don't → Composition → Accessibility → Responsive → Future

### Out of Scope

- Generated/style-guided from code comments (Storybook, StyleDictionary)
- Interactive preview (no live rendering — static docs)
- Versioned docs (document the current state)
- Visual screenshots or mockups
- Framework-specific example code (Blade examples only for now; React/Vue examples may be added later)

## Directory Structure

```
docs/style-guide/
├── README.md              # Principles, overview, how to use
├── principles.md           # Design principles
├── tokens.md               # Design token reference
├── primitives.md           # Container, grid, flex, spacing
├── alert.md
├── badge.md
├── button.md
├── form-input.md
├── card.md
├── list.md
├── nav.md
├── table.md
├── dropdown.md
├── modal.md
├── tabs.md
├── pagination.md
├── avatar.md
└── patterns.md             # Common patterns
```

## Document Templates

### `README.md`

```markdown
# Style Guide

Design system and component library for [Project Name]. Built with SCSS tokens/primitives/components and Laravel Blade components.

## Principles

See [principles.md](principles.md).

## Quick Reference

| Component | SCSS Partial | Blade Component | Spec |
|-----------|-------------|-----------------|------|
| Alert | `_alert.scss` | `<x-alert>` | Spec-019 |
| Badge | `_badge.scss` | `<x-badge>` | Spec-020 |
| ... | ... | ... | ... |

## Token Reference

See [tokens.md](tokens.md).

## Primitive Reference

See [primitives.md](primitives.md).
```

### `principles.md`

```markdown
# Design Principles

## 1. Consistency
Every component uses the same tokens. No hard-coded values.

## 2. Accessibility
Every component is keyboard-navigable and screen-reader-compatible out of the box.

## 3. Simplicity
Prefer one way to do something. Avoid variant proliferation.

## 4. Portability
SCSS layer has no framework dependencies. Copy tokens/primitives/components to any project.

## 5. Composition over Configuration
Components compose through slots, not configuration objects.
```

### `tokens.md`

One section per token file. Each lists all variables with description and default value.

```markdown
# Design Tokens

## Spacing (`tokens/_spacing.scss`)

| Variable | Value | Usage |
|----------|-------|-------|
| `$space-xs` | 4px | Tight gaps, icon padding |
| `$space-sm` | 8px | Button padding, small gaps |
| `$space-md` | 16px | Card padding, form gap |
| `$space-lg` | 24px | Section spacing |
| `$space-xl` | 32px | Page section spacing |
| `$space-2xl` | 48px | Major sections |
| `$space-3xl` | 64px | Page margins |

## Colors (`tokens/_colors.scss`)

| Variable | Value | Usage |
|----------|-------|-------|
| `$color-primary` | ... | Primary actions, active states |
| `$color-danger` | ... | Destructive actions, errors |
| ... | ... | ... |

... (repeat for typography, breakpoints, borders, shadows, z-index, transitions)
```

### `primitives.md`

```markdown
# Primitives

## Container

`.container` — max-width centered wrapper (default: 1200px)
`.container--fluid` — full-width wrapper

## Grid

`.grid` — CSS Grid container, 12-column
`.grid__col--{1-12}` — column span
`.grid__col--{sm|md|lg|xl}-{1-12}` — responsive column span

## Flex

`.flex--row` — `display: flex; flex-direction: row`
`.flex--col` — `display: flex; flex-direction: column`
`.flex--center` — center both axes
`.flex--center-between` — space-between, vertically centered
`.flex--gap-{xs|sm|md|lg|xl}` — gap utilities
```

### Component Doc Template

Each component doc follows this structure:

```markdown
# Component Name

## Purpose
_One paragraph describing what this component is for and when to use it._

## Variants
_Table or list of all variants with visual description._

| Variant | Modifier | Description |
|---------|----------|-------------|
| Primary | `variant="primary"` | Main call-to-action |
| Secondary | `variant="secondary"` | Alternative action |

## Do
```blade
{{-- Correct usage — always show the Blade invocation --}}
```

## Don't
```blade
{{-- Incorrect usage — explain why --}}
```

## Composition Rules

| Can contain | Cannot contain |
|-------------|----------------|
| ... | ... |

## Accessibility

_Which ARIA attributes are applied, what the component communicates to assistive tech, keyboard interaction notes._

## Responsive Behavior

_How the component behaves at breakpoints. If none, state "Static — no responsive changes."_

## Future Considerations

_Known gaps, planned variants, or features deferred from the spec. Optional section — omit if none._
```

### `patterns.md`

```markdown
# Common UI Patterns

## CRUD List Page

Layout for listing, creating, and deleting records:
- Container → Nav → Header (title + Create button) → Table with action dropdowns → Pagination

```blade
<x-container>
    <x-nav><!-- tabs/filters --></x-nav>

    <div class="flex flex--center-between">
        <h1 class="heading--2">Authors</h1>
        <x-button href="{{ route('authors.create') }}">Create</x-button>
    </div>

    <x-table>
        ...
    </x-table>

    <x-pagination :paginator="$authors" />
</x-container>
```

## Form with Validation

Layout for forms with server-side validation:
- Card → Card header (title) → Form → Inputs → Alert (errors) → Card footer (buttons)

```blade
<x-card>
    <x-slot:header>Edit Book</x-slot:header>

    <form method="POST" action="...">
        @csrf

        @if($errors->any())
            <x-alert variant="danger">
                <ul>@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </x-alert>
        @endif

        <x-form-input name="title" label="Title" :value="$book->title" required />
        <x-form-input name="isbn" label="ISBN" :value="$book->isbn" />

        <x-slot:footer>
            <x-button variant="secondary" href="{{ route('books.index') }}">Cancel</x-button>
            <x-button type="submit">Save</x-button>
        </x-slot:footer>
    </form>
</x-card>
```
```

## Implementation Order

1. `README.md`, `principles.md`, `tokens.md`, `primitives.md`, `patterns.md` — one batch
2. Simple components: `alert.md`, `badge.md`, `button.md`
3. Form: `form-input.md`
4. Layout: `card.md`, `list.md`, `nav.md`, `table.md`
5. Interactive: `dropdown.md`, `modal.md`, `tabs.md`
6. Utility: `pagination.md`, `avatar.md`

Each doc extracts content from the corresponding spec's Interfaces, Composition Rules, Accessibility, and Acceptance Criteria sections.

## Acceptance Criteria

1. All 13 component doc files exist at `docs/style-guide/`
2. README has links to all component docs and token/primitives/patterns pages
3. Each component doc covers all required sections
4. Each "Do" example shows correct Blade usage
5. Each "Don't" example explains why it's wrong
6. Composition rules match the component's spec exactly
7. Accessibility notes match the component's spec exactly
8. `tokens.md` lists every token from all 9 token files
9. `patterns.md` covers CRUD list page and form with validation
