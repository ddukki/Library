# ADR-0016: Alpine Migration Pattern — Blade Partial + Dedicated Alpine JS

**Status:** Approved
**Date:** 2026-06-21
**Replaces:** None
**Supersedes:** None
**Revisit Trigger:** When all Vue→Alpine migrations are complete and SCSS consolidation begins

## Context

We are migrating ~15 Vue 2 components to Alpine.js (Spec 006+). Each migration replaces a Vue component (`.vue` file + global registration + usage via custom tag in Blade) with an equivalent Alpine implementation.

The question is how to structure the Alpine equivalent: where does the HTML live, where does the JS logic live, and how do Blade page templates reference it.

## Decision

Each Vue component is replaced by **two files**:

1. **Blade partial** (`resources/views/library/<area>/_<component>.blade.php`) — the HTML template with Alpine attributes (`x-data`, `x-model`, `@click`, `x-show`, etc.). The partial is included by page templates via `@include`.

2. **Alpine JS module** (`resources/js/alpine/<component>.js`) — the Alpine `data()` function containing state, methods, and lifecycle logic. The module is imported and registered in `app.js` via `Alpine.data()`.

Page templates (e.g., `create.blade.php`, `edit.blade.php`) use `@include` to pull in the partial, passing any server-side data as variables. They contain no component-specific HTML or Alpine logic — only page-level layout.

### Example structure

```
resources/
├── views/
│   └── library/
│       └── shelves/
│           ├── create.blade.php        # @include('library.shelves._form')
│           ├── edit.blade.php          # @include('library.shelves._form')
│           └── _form.blade.php         # Alpine HTML template
└── js/
    └── alpine/
        └── shelf-form.js              # Alpine.data('shelfForm', ...)
```

## Alternatives Considered

### Inline in page templates
The HTML + Alpine attributes live directly in `create.blade.php` and `edit.blade.php`. No separate partial. JS logic either inline in a `<script>` tag or all in `app.js`.

- **Pros:** Fewer files, simpler initial structure
- **Cons:** Duplicates HTML for components used in multiple pages; page templates mix layout with component details; harder to find and modify component code
- **Verdict:** Rejected — violates separation of concerns

### Blade anonymous components
Use `<x-shelf-form>` instead of `@include`. The Blade component at `resources/views/components/shelf-form.blade.php` is auto-discovered by Laravel.

- **Pros:** Cleaner invocation syntax, auto-discovery, slots support
- **Cons:** Components directory is project-wide, mixing with layout components; anonymous components add template compilation overhead; these are intermediate artifacts that will be consolidated later
- **Verdict:** Rejected — over-engineered for transitional artifacts

### Keep as Vue components
Leave existing Vue components in place and migrate later.

- **Pros:** Zero migration cost now
- **Cons:** Defeats the purpose of the upgrade; Vue 2 dependency remains; jQuery bug in Editions.vue stays broken
- **Verdict:** Rejected — the migration is the goal

## Consequences

**Positive:**
- Page templates stay clean (layout only)
- Component HTML is in one file, easily found and modified
- Component JS is tree-shakeable and importable
- Pattern is consistent across all 15 migrations

**Negative:**
- More files than the inline approach (2 per component vs 0)
- Blade partials are intermediate artifacts — they may be merged or replaced during the later SCSS consolidation phase

**Neutral:**
- This pattern applies only to the migration phase. The SCSS consolidation spec (post-migration) may restructure how these partials and JS modules are organized.

## Compliance

All subsequent Alpine migration specs (Spec 007+) must follow this pattern: one Blade partial + one Alpine JS module per component, invoked by page templates via `@include`.
