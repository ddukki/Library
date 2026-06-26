# Spec 018: Design Tokens & SCSS Primitives

**Status:** Approved

**References:** ADR-0020

## Objective

Establish the foundation of the component library: design token variables and SCSS primitive classes/mixins. The primitive layer must be flexible enough to compose into any layout — traditional UI, full-bleed hero sections, overlapping content blocks, and cinematic typography. After this spec, `resources/sass/tokens/` and `resources/sass/primitives/` exist with all values defined. No components are built yet.

## Scope

### In Scope

- Create `resources/sass/tokens/` directory with all variable files
- Create `resources/sass/primitives/` directory with all mixin/class files
- Create `resources/sass/components/` directory (empty, placeholder)
- Update `resources/sass/app.scss` to import tokens, then primitives, then components
- All values use SCSS variables (no hard-coded values in mixins or classes)
- All files use `@use` syntax (not `@import`)
- The SCSS layer is framework-agnostic — no Laravel-specific references in tokens or primitives, so the `sass/` directory can be copied into any project

### Out of Scope

- Building any component SCSS partials or Blade components
- Replacing Bootstrap classes in any Blade view
- Removing Bootstrap SCSS imports
- Style guide documentation
- Any JavaScript or Alpine.js changes

## Interfaces

### Directory Structure

```
resources/sass/
├── tokens/                     # Design token variables (portable)
│   ├── _spacing.scss
│   ├── _colors.scss
│   ├── _typography.scss
│   ├── _breakpoints.scss
│   ├── _opacity.scss
│   ├── _borders.scss
│   ├── _shadows.scss
│   ├── _zindex.scss
│   └── _transitions.scss
├── primitives/                 # Utility classes + mixins (portable)
│   ├── _responsive.scss         # respond-to() + respond-up-to() mixins
│   ├── _container.scss          # Max-width wrappers with size variants
│   ├── _section.scss            # Full-bleed sections with vertical rhythm
│   ├── _grid.scss               # CSS Grid layouts
│   ├── _flex.scss               # Flexbox helpers + gap utilities
│   ├── _spacing.scss            # Margin/padding utilities
│   ├── _type.scss               # Heading + body + display type classes
│   ├── _ratio.scss              # Aspect-ratio boxes
│   ├── _overlap.scss            # Negative margin + z-index layering
│   ├── _sr-only.scss            # Screen-reader-only utility
│   └── _zindex.scss             # Z-index utility classes
├── components/                 # Component styles (filled by subsequent specs)
├── app.scss                    # Entry point (project-specific)
└── _variables.scss             # Bootstrap overrides (TEMPORARY — removed with Bootstrap)
```

### Token Files

**`resources/sass/tokens/_spacing.scss`:**

Based on a 4px grid. The extended scale supports component spacing up to section-level vertical rhythm:

```scss
// Component spacing
$space-xs:   4px;
$space-sm:   8px;
$space-md:   16px;
$space-lg:   24px;
$space-xl:   32px;
$space-2xl:  48px;
$space-3xl:  64px;

// Section-level spacing (for page sections, hero blocks, cinematic vertical rhythm)
$space-section-sm:   80px;
$space-section-md:   120px;
$space-section-lg:   160px;
```

**`resources/sass/tokens/_colors.scss`:**

Warm, muted palette appropriate for a library app:

```scss
// Brand / Primary
$color-primary:       #2c3e50;   // Deep navy — headings, nav, primary buttons
$color-primary-light: #3d566e;   // Hover states
$color-primary-dark:  #1a252f;   // Active states

// Accent
$color-accent:        #d4a574;   // Warm amber/copper — highlights, CTAs
$color-accent-light:  #e8c9a8;   // Hover states
$color-accent-dark:   #b8854e;   // Active states

// Neutrals
$color-white:         #ffffff;
$color-bg:            #f4f1ec;   // Warm off-white page background
$color-bg-alt:        #eae5dd;   // Card and section backgrounds
$color-border:        #d5cec4;   // Borders and dividers
$color-text:          #2c2c2c;   // Body text
$color-text-muted:    #6b6358;   // Secondary text / captions
$color-text-inverse:  #f4f1ec;   // Text on dark backgrounds

// Semantic
$color-success:       #4a7c59;   // Muted green
$color-warning:       #c9953b;   // Muted gold
$color-danger:        #9e4e4e;   // Muted red
$color-info:          #4a6fa5;   // Muted blue
```

**`resources/sass/tokens/_typography.scss`:**

```scss
// Type scale (modular scale 1.25 — major third)
// Body scale
$font-size-xs:      0.75rem;     // 12px
$font-size-sm:      0.875rem;    // 14px
$font-size-base:    1rem;        // 16px
$font-size-lg:      1.125rem;    // 18px
$font-size-xl:      1.25rem;     // 20px

// Heading scale
$font-size-2xl:     1.5rem;      // 24px
$font-size-3xl:     2rem;        // 32px

// Display scale (for cinematic hero text, large headlines)
$font-size-4xl:     2.5rem;      // 40px
$font-size-5xl:     3.25rem;     // 52px
$font-size-6xl:     4.5rem;      // 72px

// Font families
$font-family-body:   'Heebo', 'Segoe UI', Tahoma, sans-serif;
$font-family-heading: $font-family-body;  // Same for now, differentiate later

// Font weights
$font-weight-light:  300;
$font-weight-normal: 400;
$font-weight-medium: 500;
$font-weight-bold:   700;
$font-weight-black:  900;

// Letter spacing
$letter-spacing-tight:  -0.02em;
$letter-spacing-normal:  0;
$letter-spacing-wide:    0.05em;

// Line heights
$line-height-none:  1;
$line-height-tight:  1.2;
$line-height-base:   1.5;
$line-height-loose:  1.75;
```

**`resources/sass/tokens/_breakpoints.scss`:**

```scss
$bp-sm:  576px;
$bp-md:  768px;
$bp-lg:  992px;
$bp-xl:  1200px;
$bp-2xl: 1400px;
```

**`resources/sass/tokens/_opacity.scss`:**

```scss
$opacity-0:    0;
$opacity-10:   0.1;
$opacity-20:   0.2;
$opacity-30:   0.3;
$opacity-40:   0.4;
$opacity-50:   0.5;
$opacity-60:   0.6;
$opacity-70:   0.7;
$opacity-80:   0.8;
$opacity-90:   0.9;
$opacity-100:  1;
```

**`resources/sass/tokens/_borders.scss`:**

```scss
$radius-none:  0;
$radius-sm:    4px;
$radius-md:    8px;
$radius-lg:    12px;
$radius-full:  9999px;

$border-width-thin:   1px;
$border-width-thick:  2px;
```

**`resources/sass/tokens/_shadows.scss`:**

```scss
$shadow-sm:   0 1px 2px rgba(44, 62, 80, 0.08);
$shadow-md:   0 2px 8px rgba(44, 62, 80, 0.1);
$shadow-lg:   0 4px 16px rgba(44, 62, 80, 0.12);
$shadow-xl:   0 8px 32px rgba(44, 62, 80, 0.16);
```

**`resources/sass/tokens/_zindex.scss`:**

```scss
$z-dropdown:   100;
$z-sticky:     200;
$z-modal-bg:   300;
$z-modal:      400;
$z-toast:      500;
```

**`resources/sass/tokens/_transitions.scss`:**

```scss
$transition-fast:    150ms ease;
$transition-base:    250ms ease;
$transition-slow:    400ms ease;
```

### Primitive Files

**`resources/sass/primitives/_responsive.scss`:**

```scss
@use '../tokens/breakpoints' as *;

// Min-width (mobile-first)
@mixin respond-to($bp) {
    @if $bp == 'sm' {
        @media (min-width: $bp-sm) { @content; }
    } @else if $bp == 'md' {
        @media (min-width: $bp-md) { @content; }
    } @else if $bp == 'lg' {
        @media (min-width: $bp-lg) { @content; }
    } @else if $bp == 'xl' {
        @media (min-width: $bp-xl) { @content; }
    } @else if $bp == '2xl' {
        @media (min-width: $bp-2xl) { @content; }
    }
}

// Max-width (downward)
@mixin respond-up-to($bp) {
    @if $bp == 'sm' {
        @media (max-width: ($bp-sm - 1px)) { @content; }
    } @else if $bp == 'md' {
        @media (max-width: ($bp-md - 1px)) { @content; }
    } @else if $bp == 'lg' {
        @media (max-width: ($bp-lg - 1px)) { @content; }
    } @else if $bp == 'xl' {
        @media (max-width: ($bp-xl - 1px)) { @content; }
    } @else if $bp == '2xl' {
        @media (max-width: ($bp-2xl - 1px)) { @content; }
    }
}
```

**`resources/sass/primitives/_container.scss`:**

Centered max-width wrappers with padding. Default `.container` uses the xl breakpoint as max-width. Fluid for full-bleed:

```scss
@use '../tokens/spacing' as *;
@use '../tokens/breakpoints' as *;
@use 'responsive' as *;

.container {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    padding-left: $space-md;
    padding-right: $space-md;

    max-width: $bp-xl;

    @include respond-to('2xl') { max-width: $bp-2xl; }
}

.container--sm  { max-width: $bp-sm; }
.container--md  { max-width: $bp-md; }
.container--lg  { max-width: $bp-lg; }
.container--xl  { max-width: $bp-xl; }

.container--fluid {
    max-width: none;
}
```

Default container width changed from `$bp-sm` to `$bp-xl` — the old Bootstrap convention (narrow container) isn't suitable for cinematic layouts. Use `container--sm` for constrained content (auth forms, reading views).

**`resources/sass/primitives/_section.scss`:**

Full-bleed page sections with controlled vertical rhythm. A section breaks out of its container to the viewport edges while centering its content via `.container`:

```scss
@use '../tokens/spacing' as *;
@use '../tokens/colors' as *;

.section {
    width: 100%;
    padding-top: $space-section-md;
    padding-bottom: $space-section-md;

    &--sm  { padding-top: $space-section-sm; padding-bottom: $space-section-sm; }
    &--lg  { padding-top: $space-section-lg; padding-bottom: $space-section-lg; }

    // Background variants (.section--dark, .section--muted, etc.)
    // Defined by the project's color token overrides

    &--overlay {
        position: relative;
        overflow: hidden;

        > * {
            position: relative;
            z-index: 1;
        }

        &::before {
            content: '';
            position: absolute;
            inset: 0;
            // Background image set inline or by project
            z-index: 0;
        }
    }
}
```

**`resources/sass/primitives/_ratio.scss`:**

Aspect-ratio boxes for images, hero backgrounds, and media blocks:

```scss
.ratio {
    position: relative;
    width: 100%;
    overflow: hidden;

    &::before {
        content: '';
        display: block;
        width: 100%;
    }
}

.ratio--16\9::before   { padding-bottom: 56.25%; }
.ratio--21\9::before   { padding-bottom: 42.857%; }
.ratio--4\3::before    { padding-bottom: 75%; }
.ratio--1\1::before    { padding-bottom: 100%; }
.ratio--3\2::before    { padding-bottom: 66.667%; }
.ratio--cinema::before { padding-bottom: 41.667%; } // 2.4:1 — ultrawide cinematic

.ratio__content {
    position: absolute;
    inset: 0;
    object-fit: cover;
    width: 100%;
    height: 100%;
}
```

**`resources/sass/primitives/_overlap.scss`:**

Composable overlap patterns for layered/cinematic layouts:

```scss
@use '../tokens/spacing' as *;
@use '../tokens/zindex' as *;

.overlap {
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: 1fr;

    > * {
        grid-area: 1 / 1;
    }
}

// Pull elements up over the preceding sibling
.overlap--pull-up-sm { margin-top: -$space-lg; }
.overlap--pull-up-md { margin-top: -$space-2xl; }
.overlap--pull-up-lg { margin-top: -$space-3xl; }

// Offset from center
.overlap--right { margin-left: 15%; }
.overlap--left  { margin-right: 15%; }
```

**`resources/sass/primitives/_zindex.scss`:**

```scss
@use '../tokens/zindex' as *;

.z-dropdown { z-index: $z-dropdown; }
.z-sticky   { z-index: $z-sticky; }
.z-modal-bg { z-index: $z-modal-bg; }
.z-modal    { z-index: $z-modal; }
.z-toast    { z-index: $z-toast; }
```

**`resources/sass/primitives/_grid.scss`:**

```scss
@use '../tokens/spacing' as *;
@use '../tokens/breakpoints' as *;
@use 'responsive' as *;

.grid {
    display: grid;
    gap: $space-md;
}

.grid--cols-2  { grid-template-columns: repeat(2, 1fr); }
.grid--cols-3  { grid-template-columns: repeat(3, 1fr); }
.grid--cols-4  { grid-template-columns: repeat(4, 1fr); }

@include respond-to('sm') { .grid\@sm { display: grid; gap: $space-md; } }
@include respond-to('md') { .grid\@md { display: grid; gap: $space-md; } }
@include respond-to('lg') { .grid\@lg { display: grid; gap: $space-md; } }
```

**`resources/sass/primitives/_flex.scss`:**

```scss
@use '../tokens/spacing' as *;
@use '../tokens/breakpoints' as *;
@use 'responsive' as *;

.flex--row    { display: flex; flex-direction: row; }
.flex--col    { display: flex; flex-direction: column; }
.flex--wrap   { flex-wrap: wrap; }
.flex--center { display: flex; align-items: center; justify-content: center; }
.flex--between { display: flex; justify-content: space-between; align-items: center; }
.flex--gap-xs  { gap: $space-xs; }
.flex--gap-sm  { gap: $space-sm; }
.flex--gap-md  { gap: $space-md; }
.flex--gap-lg  { gap: $space-lg; }
```

**`resources/sass/primitives/_spacing.scss`:**

Generated from the spacing token map:

```scss
@use '../tokens/spacing' as *;

// Component-level margins
.mt-xs { margin-top: $space-xs; }
.mt-sm { margin-top: $space-sm; }
.mt-md { margin-top: $space-md; }
.mt-lg { margin-top: $space-lg; }
.mt-xl { margin-top: $space-xl; }

.mb-xs { margin-bottom: $space-xs; }
.mb-sm { margin-bottom: $space-sm; }
.mb-md { margin-bottom: $space-md; }
.mb-lg { margin-bottom: $space-lg; }
.mb-xl { margin-bottom: $space-xl; }

// Section-level margins
.mt-section-sm { margin-top: $space-section-sm; }
.mt-section-md { margin-top: $space-section-md; }
.mt-section-lg { margin-top: $space-section-lg; }

.mb-section-sm { margin-bottom: $space-section-sm; }
.mb-section-md { margin-bottom: $space-section-md; }
.mb-section-lg { margin-bottom: $space-section-lg; }

// Component-level padding
.p-xs { padding: $space-xs; }
.p-sm { padding: $space-sm; }
.p-md { padding: $space-md; }
.p-lg { padding: $space-lg; }

.px-md { padding-left: $space-md; padding-right: $space-md; }
.py-md { padding-top: $space-md; padding-bottom: $space-md; }
.py-lg { padding-top: $space-lg; padding-bottom: $space-lg; }

// Section-level padding
.py-section-sm { padding-top: $space-section-sm; padding-bottom: $space-section-sm; }
.py-section-md { padding-top: $space-section-md; padding-bottom: $space-section-md; }
.py-section-lg { padding-top: $space-section-lg; padding-bottom: $space-section-lg; }
```

**`resources/sass/primitives/_type.scss`:**

```scss
@use '../tokens/typography' as *;
@use '../tokens/colors' as *;

// Display sizes (cinematic hero text)
.display--1 { font-size: $font-size-6xl; font-weight: $font-weight-black; line-height: $line-height-none; letter-spacing: $letter-spacing-tight; color: $color-text; }
.display--2 { font-size: $font-size-5xl; font-weight: $font-weight-bold; line-height: $line-height-tight; letter-spacing: $letter-spacing-tight; color: $color-text; }
.display--3 { font-size: $font-size-4xl; font-weight: $font-weight-bold; line-height: $line-height-tight; letter-spacing: $letter-spacing-normal; color: $color-text; }

// Heading sizes
.heading--1 { font-size: $font-size-3xl; font-weight: $font-weight-bold; line-height: $line-height-tight; color: $color-primary; }
.heading--2 { font-size: $font-size-2xl; font-weight: $font-weight-bold; line-height: $line-height-tight; color: $color-primary; }
.heading--3 { font-size: $font-size-xl; font-weight: $font-weight-medium; line-height: $line-height-tight; color: $color-primary; }
.heading--4 { font-size: $font-size-lg; font-weight: $font-weight-medium; line-height: $line-height-tight; color: $color-primary; }

// Body sizes
.body       { font-size: $font-size-base; font-weight: $font-weight-normal; line-height: $line-height-base; color: $color-text; }
.body--sm   { font-size: $font-size-sm; font-weight: $font-weight-normal; line-height: $line-height-base; color: $color-text; }
.body--lg   { font-size: $font-size-lg; font-weight: $font-weight-normal; line-height: $line-height-loose; color: $color-text; }

// Meta
.caption    { font-size: $font-size-xs; font-weight: $font-weight-normal; line-height: $line-height-base; color: $color-text-muted; }
```

**`resources/sass/primitives/_sr-only.scss`:**

```scss
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
```

### Entry Point Changes

**`resources/sass/app.scss`:**

Replace existing content:

```scss
// 1. Design Tokens (portable)
@use 'tokens/spacing' as *;
@use 'tokens/colors' as *;
@use 'tokens/typography' as *;
@use 'tokens/breakpoints' as *;
@use 'tokens/opacity' as *;
@use 'tokens/borders' as *;
@use 'tokens/shadows' as *;
@use 'tokens/zindex' as *;
@use 'tokens/transitions' as *;

// 2. Primitives (portable)
@use 'primitives/responsive';
@use 'primitives/container';
@use 'primitives/section';
@use 'primitives/grid';
@use 'primitives/flex';
@use 'primitives/spacing';
@use 'primitives/type';
@use 'primitives/ratio';
@use 'primitives/overlap';
@use 'primitives/sr-only';
@use 'primitives/zindex';

// 3. Components (filled by subsequent specs)
// (empty — components added here as they are built)

// ──── Project-specific imports below this line ────

// 4. Bootstrap overrides (TEMPORARY — removed with Bootstrap)
@use 'variables' as *;

// 5. Bootstrap (TEMPORARY — removed with Bootstrap)
@use '~bootstrap/scss/bootstrap' as *;

// Fonts
@import url('https://fonts.googleapis.com/css?family=Heebo');

// Legacy overrides (kept during migration)
a.card-link {
    text-decoration: none;
}

a {
    cursor: pointer;
}
```

## Acceptance Criteria

1. `resources/sass/tokens/` directory exists with all 9 files
2. `resources/sass/primitives/` directory exists with all 11 files
3. `resources/sass/components/` directory exists (can be empty)
4. `resources/sass/app.scss` imports tokens → primitives → components → project-specific overrides
5. `npm run build` succeeds with no SCSS errors
6. `npm run dev` starts without SCSS compilation errors
7. No `@import` statements in any new file (all use `@use`)
8. Every hard-coded value in primitives references a token variable (verify via grep for hex colors, pixel values, rem values in `primitives/*.scss`)
9. `.display--1` through `.display--3` render at correct font sizes (>2rem)
10. `.section`, `.section--sm`, `.section--lg` render with correct vertical padding
11. `.container`, `.container--sm`, `.container--md`, `.container--lg`, `.container--xl`, `.container--fluid` each constrain width correctly
12. `.ratio--16\9`, `.ratio--21\9`, `.ratio--4\3`, `.ratio--1\1`, `.ratio--3\2`, `.ratio--cinema` each produce correct aspect ratio
13. `.overlap` creates a stacked grid context; `.overlap--pull-up-*` moves the child up over preceding content
14. `.z-*` classes apply correct z-index values
15. `respond-up-to('sm')` generates a `max-width` media query (not `min-width`)
16. `py-section-*` and `mb-section-*` classes use section-level spacing values (80px+)

## Edge Cases

- **File ordering matters** — `@use` is order-dependent in Dart Sass. Tokens must be loaded before primitives, which must be loaded before components. The entry point enforces this ordering.
- **`_variables.scss` conflict** — the old `_variables.scss` defines Bootstrap overrides (`$primary`, `$font-family-sans-serif`). The new token system uses different names (`$color-primary`, `$font-family-body`), so no collisions during migration.
- **Responsive mixin not global** — component partials that need `respond-to()` or `respond-up-to()` must explicitly `@use 'primitives/responsive' as *;` in each file. Dart Sass `@use` doesn't forward automatically.
- **`padding-bottom` aspect-ratio technique** — the `.ratio` primitive uses the `padding-bottom` hack for browser compatibility. Once `aspect-ratio` CSS property hits full global support (currently ~94%), consider migrating. The `ratio__content` child uses `position: absolute` which must be accounted for in consumer layouts.
- **Negative margins in overlap** — `.overlap--pull-up-*` uses negative `margin-top`. This pulls the overlapping element up visually but does not change the document flow. Adjacent elements will not reflow to fill the gap. Use with explicit spacing or within `.section` for controlled overlap.
- **Maximum nesting depth for overlap** — overlapping more than 3 elements in the same `.overlap` grid is not recommended. Each layer adds complexity for click and focus targets. For heavy layered layouts, compose multiple `.overlap` instances.
- **Portability note** — the `sass/` directory (tokens + primitives) contains no Laravel-specific references and can be copied directly into any SCSS-compiled project. Blade components are Laravel-specific and remain in `resources/views/components/`.

## Implementation Order

1. Create `resources/sass/tokens/` directory with all 8 files
2. Create `resources/sass/primitives/` directory with all 7 files
3. Create `resources/sass/components/` directory (empty)
4. Update `resources/sass/app.scss` with new import structure
5. Run `npm run build` and fix any SCSS errors
6. Verify no visual regression on any page (Bootstrap still loaded, no component styles yet)
