# ADR-0019: Accessible ARIA Labels as Test Locators

**Status:** Approved
**Date:** 2026-06-22
**Replaces:** None
**Supersedes:** None

## Context

Playwright e2e tests need reliable locators for interactive elements (buttons, links, inputs). The current approach uses CSS class selectors like `button:has(.fa-edit)` and `.col-2 > button`, which are brittle — they break on layout changes, CSS refactoring, or when Font Awesome replaces `<i>` tags with SVGs.

Playwright's recommended locator priority is:
1. **Role locators** (`getByRole('button', { name: '...' })`) — most resilient
2. Label locators (`getByLabel`)
3. Text locators (`getByText`)
4. Test ID locators (`getByTestId`) — last resort

Many buttons in this app are icon-only (Font Awesome with no text), which means role/text locators can't resolve them without explicit naming.

## Decision Drivers

- Locators must survive layout/CSS changes
- Must not add test-specific attributes to production HTML
- Must follow Playwright's recommended best practices
- Should improve accessibility for actual users

## Considered Options

### A. ARIA labels on icon-only buttons

Add `aria-label` attributes to icon-only interactive elements. This is an accessibility best practice — screen readers need labels for icon-only buttons. Tests use `getByRole('button', { name: '...' })` to target them.

```html
<!-- Before -->
<button class="btn btn-sm btn-primary" x-on:click="toggleEdit(index)">
    <i class="fas fa-edit"></i>
</button>

<!-- After -->
<button aria-label="Edit edition" class="btn btn-sm btn-primary" x-on:click="toggleEdit(index)">
    <i class="fas fa-edit"></i>
</button>
```

Test:
```js
page.getByRole('button', { name: 'Edit edition' })
```

### B. Text labels beside icons

Add visible text to every button. Bulletproof for both accessibility and tests, but visually noisy in a data-dense UI (editions table, shelf manager).

### C. CSS class–based locators

Status quo. Fragile — changes to Bootstrap classes, DOM order, or Font Awesome rendering break tests silently.

## Decision Outcome

**Chosen: Option A — ARIA labels on icon-only buttons.**

### Rationale

1. **Accessibility requires it.** Icon-only buttons without alt text or aria-label are invisible to screen readers. Adding labels is fixing a real a11y bug, not a test hack.

2. **Role locators are the most resilient Playwright pattern.** They don't depend on CSS classes, DOM order, or rendering details. A button can move columns, change color, or switch icon libraries and the locator still works.

3. **No production-only attributes.** `aria-label` is a standard HTML accessibility attribute — it belongs in production regardless of testing.

4. **Incremental adoption.** Add labels as we write tests. No need to retrofit the entire codebase at once.

### Consequences

**Positive:**
- Tests use stable, semantic locators
- Improved screen reader support
- No test-specific attributes in production HTML
- Layout/CSS changes don't break locators

**Negative:**
- Must remember to add aria-labels to new icon-only interactive elements
- Slightly more verbose Blade templates

**Neutral:**
- Existing CSS locators can be migrated incrementally as tests are written

## Compliance

1. All icon-only buttons must have an `aria-label` describing the action.
2. Playwright tests must prefer role locators (`getByRole`) over CSS/text locators for interactive elements.
3. Form inputs with explicit `<label>` elements should use `getByLabel` over placeholder selectors.
