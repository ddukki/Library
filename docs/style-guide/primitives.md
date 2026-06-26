# Primitives

## Container

Centered max-width wrappers with padding.

| Class | Max-width | Use case |
|-------|-----------|----------|
| `.container` | 1200px (xl) | Default page layout |
| `.container--sm` | 576px | Auth forms, reading views |
| `.container--md` | 768px | Medium constrained content |
| `.container--lg` | 992px | Wide content |
| `.container--xl` | 1200px | Same as default |
| `.container--fluid` | none | Full-bleed sections |

## Grid

CSS Grid layout, 12-column pattern using repeat.

| Class | Description |
|-------|-------------|
| `.grid` | CSS Grid container, gap: 16px |
| `.grid--cols-2` | 2 equal columns |
| `.grid--cols-3` | 3 equal columns |
| `.grid--cols-4` | 4 equal columns |
| `.grid@sm` | Grid activates at sm+ |
| `.grid@md` | Grid activates at md+ |
| `.grid@lg` | Grid activates at lg+ |

## Flex

Flexbox helpers.

| Class | Description |
|-------|-------------|
| `.flex--row` | `display: flex; flex-direction: row` |
| `.flex--col` | `display: flex; flex-direction: column` |
| `.flex--wrap` | `flex-wrap: wrap` |
| `.flex--center` | Center both axes |
| `.flex--between` | Space-between, vertically centered |
| `.flex--gap-xs` | gap: 4px |
| `.flex--gap-sm` | gap: 8px |
| `.flex--gap-md` | gap: 16px |
| `.flex--gap-lg` | gap: 24px |

## Spacing Utilities

Component-level margins (`$space-xs` through `$space-xl`). Section-level margins (`$space-section-sm` through `$space-section-lg`).

### Margins

`.mt-{xs|sm|md|lg|xl}` — top margin
`.mb-{xs|sm|md|lg|xl}` — bottom margin
`.mt-section-{sm|md|lg}` — section-level top margin
`.mb-section-{sm|md|lg}` — section-level bottom margin

### Padding

`.p-{xs|sm|md|lg}` — all sides
`.px-md` — horizontal padding
`.py-md` — vertical padding
`.py-lg` — vertical padding large
`.py-section-{sm|md|lg}` — section-level vertical padding

## Type

Display, heading, body, and caption classes.

| Class | Font Size | Weight | Usage |
|-------|-----------|--------|-------|
| `.display--1` | 4.5rem (72px) | 900 | Hero text |
| `.display--2` | 3.25rem (52px) | 700 | Section hero |
| `.display--3` | 2.5rem (40px) | 700 | Page hero |
| `.heading--1` | 2rem (32px) | 700 | Page title |
| `.heading--2` | 1.5rem (24px) | 700 | Section title |
| `.heading--3` | 1.25rem (20px) | 500 | Card title |
| `.heading--4` | 1.125rem (18px) | 500 | Subsection |
| `.body` | 1rem (16px) | 400 | Default body |
| `.body--sm` | 0.875rem (14px) | 400 | Small body |
| `.body--lg` | 1.125rem (18px) | 400 | Large body |
| `.caption` | 0.75rem (12px) | 400 | Muted caption |

## Section

Full-bleed page sections with vertical rhythm.

| Class | Description |
|-------|-------------|
| `.section` | Default vertical padding (120px) |
| `.section--sm` | Reduced padding (80px) |
| `.section--lg` | Increased padding (160px) |
| `.section--overlay` | Positioned overlay with `::before` |

## Ratio

Aspect-ratio boxes (padding-bottom technique).

| Class | Ratio |
|-------|-------|
| `.ratio--16\9` | 16:9 (56.25%) |
| `.ratio--21\9` | 21:9 (42.857%) |
| `.ratio--4\3` | 4:3 (75%) |
| `.ratio--1\1` | 1:1 (100%) |
| `.ratio--3\2` | 3:2 (66.667%) |
| `.ratio--cinema` | 2.4:1 (41.667%) |

## Overlap

Composable overlap patterns for layered layouts.

| Class | Description |
|-------|-------------|
| `.overlap` | CSS Grid stacking context |
| `.overlap--pull-up-sm` | `margin-top: -24px` |
| `.overlap--pull-up-md` | `margin-top: -48px` |
| `.overlap--pull-up-lg` | `margin-top: -64px` |
| `.overlap--right` | `margin-left: 15%` |
| `.overlap--left` | `margin-right: 15%` |

## Z-Index Utilities

| Class | Value |
|-------|-------|
| `.z-dropdown` | 100 |
| `.z-sticky` | 200 |
| `.z-modal-bg` | 300 |
| `.z-modal` | 400 |
| `.z-toast` | 500 |

## Screen-Reader Only

`.sr-only` — visually hidden, available to screen readers.
