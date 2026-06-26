# Spec 025: Nav Component

**Status:** Approved

**References:** ADR-0020, Spec 018

## Objective

Build the Nav component — the app's primary navigation: top navbar, sidebar menu, and context navigation. Must support horizontal and vertical orientations, active state indicators, responsive collapse, and integration with the Dropdown component for user menus.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_nav.scss`
- Blade component: `resources/views/components/nav.blade.php`
- Blade sub-components: `nav-link`, `nav-brand`, `nav-toggle` (collapse button)
- Two orientations: `horizontal` (default) and `vertical`
- Active state styling (current page / section)
- Responsive collapse (toggled by `nav-toggle` + Alpine.js)
- Brand slot (logo/site name on the left)
- Link items with optional icon
- Dark variant for use on colored/hero backgrounds
- Sticky positioning support

### Out of Scope

- Dropdown menus inside nav (handled by Dropdown component, composed inside nav)
- Breadcrumb navigation (separate pattern, consider future component)
- Tab navigation (handled by Tabs component)

## Interfaces

### SCSS: `resources/sass/components/_nav.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/shadows' as *;
@use '../tokens/transitions' as *;
@use '../primitives/responsive' as *;

.nav {
    display: flex;
    align-items: center;
    padding: 0 $space-md;
    background: $color-white;
    border-bottom: $border-width-thin solid $color-border;
    min-height: 56px;
    gap: $space-sm;

    // Vertical
    &--vertical {
        flex-direction: column;
        align-items: stretch;
        min-height: auto;
        border-bottom: none;
        border-right: $border-width-thin solid $color-border;
        padding: $space-md;
        gap: 0;
    }

    // Dark variant
    &--dark {
        background: $color-primary;
        border-color: $color-primary-dark;
        color: $color-text-inverse;

        .nav__link {
            color: rgba($color-text-inverse, 0.85);

            &:hover {
                color: $color-text-inverse;
                background: rgba($color-white, 0.1);
            }

            &.nav__link--active {
                color: $color-text-inverse;
                background: rgba($color-white, 0.15);
            }
        }

        .nav__brand {
            color: $color-text-inverse;
        }

        .nav__toggle {
            color: $color-text-inverse;
        }
    }

    // Sticky
    &--sticky {
        position: sticky;
        top: 0;
        z-index: $z-sticky;
    }

    // Brand / logo
    &__brand {
        font-size: $font-size-lg;
        font-weight: $font-weight-bold;
        color: $color-primary;
        text-decoration: none;
        margin-right: $space-lg;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: $space-sm;

        &:hover {
            text-decoration: none;
        }
    }

    // Link list (horizontal wrapper)
    &__links {
        display: flex;
        align-items: center;
        gap: $space-xs;
        list-style: none;
        margin: 0;
        padding: 0;

        .nav--vertical & {
            flex-direction: column;
            width: 100%;
            gap: 2px;
        }
    }

    // Individual link
    &__link {
        display: flex;
        align-items: center;
        gap: $space-sm;
        padding: $space-sm $space-md;
        font-size: $font-size-sm;
        font-weight: $font-weight-medium;
        color: $color-text;
        text-decoration: none;
        border-radius: $radius-sm;
        transition: background $transition-fast, color $transition-fast;

        &:hover {
            background: $color-bg-alt;
            color: $color-primary;
            text-decoration: none;
        }

        &.nav__link--active {
            background: rgba($color-primary, 0.08);
            color: $color-primary;
            font-weight: $font-weight-bold;
        }

        .nav--vertical & {
            padding: $space-sm $space-md;
            border-radius: $radius-sm;
            width: 100%;
        }

        // Link icon
        .nav__icon {
            width: 1em;
            height: 1em;
            flex-shrink: 0;
        }
    }

    // Toggle button (for responsive collapse)
    &__toggle {
        display: none;
        background: none;
        border: none;
        color: $color-text;
        font-size: $font-size-xl;
        cursor: pointer;
        padding: $space-xs;
        margin-left: auto;

        @include respond-up-to('md') {
            display: block;
        }
    }

    // Collapsible wrapper (hidden on mobile until toggled)
    &__collapse {
        display: flex;
        align-items: center;
        flex: 1;
        gap: $space-sm;

        @include respond-up-to('md') {
            display: none;
            flex-direction: column;
            width: 100%;
            padding: $space-md 0;

            &.nav__collapse--open {
                display: flex;
            }

            .nav__links {
                flex-direction: column;
                width: 100%;
            }
        }
    }

    // Right-aligned section (user menu, settings)
    &__end {
        display: flex;
        align-items: center;
        gap: $space-sm;
        margin-left: auto;
        flex-shrink: 0;
    }
}
```

### Blade Components

**`resources/views/components/nav.blade.php`:**

```blade
@props([
    'variant' => 'light',     // light | dark
    'orientation' => 'horizontal', // horizontal | vertical
    'sticky' => false,
])

@php
$classes = 'nav';
if ($variant === 'dark') $classes .= ' nav--dark';
if ($orientation === 'vertical') $classes .= ' nav--vertical';
if ($sticky) $classes .= ' nav--sticky';
@endphp

<nav {{ $attributes->merge(['class' => $classes]) }}>
    @if (isset($brand))
        <div class="nav__brand">
            {{ $brand }}
        </div>
    @endif

    {{-- Toggle button for mobile --}}
    @if ($orientation === 'horizontal')
        <button type="button" class="nav__toggle" aria-label="Toggle navigation"
            @click="navOpen = !navOpen"
            :aria-expanded="navOpen">
            &#9776;
        </button>
    @endif

    <div class="nav__collapse" x-data="{ navOpen: false }"
        :class="{ 'nav__collapse--open': navOpen }">
        @if (isset($links))
            <ul class="nav__links">
                {{ $links }}
            </ul>
        @endif

        @if (isset($end))
            <div class="nav__end">
                {{ $end }}
            </div>
        @endif
    </div>
</nav>
```

**`resources/views/components/nav-link.blade.php`:**

```blade
@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
])

@php
$classes = 'nav__link';
if ($active) $classes .= ' nav__link--active';
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}
        @if($active) aria-current="page" @endif>
        @if ($icon)
            <span class="nav__icon" aria-hidden="true">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </a>
</li>
```

**`resources/views/components/nav-brand.blade.php`:**

```blade
@props([
    'href' => '/',
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'nav__brand']) }}>
    {{ $slot }}
</a>
```

### Usage

```blade
{{-- Horizontal nav with brand and links --}}
<x-nav>
    <x-slot:brand>
        <x-nav-brand href="/">Library</x-nav-brand>
    </x-slot:brand>

    <x-slot:links>
        <x-nav-link href="/home" :active="request()->routeIs('home')">Home</x-nav-link>
        <x-nav-link href="/books" :active="request()->routeIs('books.*')">Books</x-nav-link>
        <x-nav-link href="/authors" :active="request()->routeIs('authors.*')">Authors</x-nav-link>
    </x-slot:links>

    <x-slot:end>
        <x-dropdown>
            <x-slot:trigger>{{ auth()->user()->name }}</x-slot:trigger>
            <x-dropdown-link href="/profile">Profile</x-dropdown-link>
            <x-dropdown-link href="/logout">Logout</x-dropdown-link>
        </x-dropdown>
    </x-slot:end>
</x-nav>

{{-- Dark variant --}}
<x-nav variant="dark" sticky>
    <x-slot:brand>...</x-slot:brand>
    <x-slot:links>...</x-slot:links>
</x-nav>

{{-- Vertical sidebar --}}
<x-nav orientation="vertical">
    <x-slot:links>
        <x-nav-link href="/dashboard" :active="$section === 'dashboard'" icon="...">Dashboard</x-nav-link>
        <x-nav-link href="/settings" :active="$section === 'settings'" icon="...">Settings</x-nav-link>
    </x-slot:links>
</x-nav>
```

## Composition Rules

| Can contain | Can be inside |
|---|---|
| Brand slot (logo/site name) | Page header / layout shell |
| Links slot (nav-link items) | Any page container |
| End slot (user menu, actions) | — |
| nav-toggle (responsive collapse) | — |

| Cannot contain | Cannot be inside |
|---|---|
| Cards, forms, or page content | Card body |
| Alert or badge directly (links only) | Modal body |
| Multiple navs in one (use sections) | Dropdown menu |

**Responsive behavior:**
- Horizontal nav collapses at `md` breakpoint (`768px`)
- Toggle button is hidden above `md`, visible below
- Collapse wrapper switches to column layout when open on mobile
- Vertical nav does not collapse (always visible)

## Accessibility

- Root element is `<nav>` with implicit `navigation` landmark role
- Active links get `aria-current="page"`
- Toggle button has `aria-label="Toggle navigation"` and `aria-expanded`
- Nav links are wrapped in `<li>` inside `<ul>` for semantic list structure
- Icon-only nav links must include screen-reader text or `aria-label`

## Acceptance Criteria

1. `_nav.scss` compiles without errors
2. Horizontal nav renders brand on left, links in center, end section on right
3. Vertical nav renders brand on top, links stacked below
4. `nav__link--active` renders with highlighted background and bold text
5. Active `<a>` gets `aria-current="page"`
6. Dark variant renders with `$color-primary` background and inverse text
7. Sticky variant applies `position: sticky; top: 0`
8. Toggle button is hidden above `md` breakpoint, visible below
9. Collapse wrapper hides on mobile by default, shows when `nav__collapse--open`
10. `nav__end` renders on the far right in horizontal mode
