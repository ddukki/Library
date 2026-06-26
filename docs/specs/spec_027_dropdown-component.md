# Spec 027: Dropdown Component

**Status:** Approved

**References:** ADR-0020, Spec 018, Spec 025 (Nav integration)

## Objective

Build the Dropdown component — a toggleable menu for user menus, action lists, and "more options" patterns. Used in the nav bar (user account menu), table rows (per-item actions), and any context menu pattern.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_dropdown.scss`
- Blade component: `resources/views/components/dropdown.blade.php`
- Blade sub-component: `resources/views/components/dropdown-link.blade.php`
- Toggle button (trigger)
- Menu panel (positioned below trigger)
- Three placements: `left` (default), `right`, `center`
- Divider between menu item groups
- Disabled menu item styling
- Open/close state built in via Alpine.js (`x-data`, `x-show`, `@click.outside`)
- Caret indicator on toggle

### Out of Scope

- Hover-open dropdowns (UX anti-pattern on mobile; use click-toggle only)
- Mega menus (separate spec, Spec 035)
- Sub-menus / nested dropdowns (future if needed)
- Dropdown with icons (consumer adds icon inside slot)

## Interfaces

### SCSS: `resources/sass/components/_dropdown.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/shadows' as *;
@use '../tokens/transitions' as *;
@use '../tokens/zindex' as *;

.dropdown {
    position: relative;
    display: inline-flex;
}

// Toggle button
.dropdown__toggle {
    display: inline-flex;
    align-items: center;
    gap: $space-xs;
    cursor: pointer;
    white-space: nowrap;

    // Caret indicator
    &::after {
        content: '';
        display: inline-block;
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid currentColor;
        margin-left: $space-xs;
        transition: transform $transition-fast;
    }

    .dropdown--open &::after {
        transform: rotate(180deg);
    }
}

// Menu panel
.dropdown__menu {
    position: absolute;
    top: 100%;
    margin-top: $space-xs;
    min-width: 180px;
    background: $color-white;
    border: $border-width-thin solid $color-border;
    border-radius: $radius-md;
    box-shadow: $shadow-md;
    padding: $space-xs 0;
    z-index: $z-dropdown;
    display: none;

    .dropdown--open & {
        display: block;
    }

    // Placements
    &--right {
        right: 0;
        left: auto;
    }

    &--center {
        left: 50%;
        transform: translateX(-50%);
    }
}

// Menu items
.dropdown__item {
    display: flex;
    align-items: center;
    gap: $space-sm;
    padding: $space-sm $space-md;
    font-size: $font-size-sm;
    color: $color-text;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: background $transition-fast, color $transition-fast;

    &:hover {
        background: $color-bg-alt;
        color: $color-primary;
        text-decoration: none;
    }

    &:active {
        background: darken($color-bg-alt, 5%);
    }

    &.dropdown__item--disabled {
        opacity: 0.4;
        pointer-events: none;
        cursor: default;
    }
}

// Divider between item groups
.dropdown__divider {
    height: 1px;
    background: $color-border;
    margin: $space-xs 0;
}
```

### Blade Components

**`resources/views/components/dropdown.blade.php`:**

```blade
@props([
    'placement' => 'left',  // left | right | center
])

@php
$placementClass = match($placement) {
    'right' => 'dropdown__menu--right',
    'center' => 'dropdown__menu--center',
    default => '',
};
@endphp

<div x-data="{ open: false }"
    {{ $attributes->merge(['class' => 'dropdown']) }}
    :class="{ 'dropdown--open': open }">

    {{-- Trigger --}}
    <div class="dropdown__toggle" @click="open = !open" @keydown.escape="open = false"
        role="button" tabindex="0" :aria-expanded="open" aria-haspopup="true">
        {{ $trigger }}
    </div>

    {{-- Menu --}}
    <div class="dropdown__menu {{ $placementClass }}" x-show="open"
        @click.outside="open = false" x-cloak>
        {{ $slot }}
    </div>
</div>
```

**`resources/views/components/dropdown-link.blade.php`:**

```blade
@props([
    'href' => '#',
    'disabled' => false,
])

@php
$classes = 'dropdown__item';
if ($disabled) $classes .= ' dropdown__item--disabled';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled) aria-disabled="true" tabindex="-1" @endif>
    {{ $slot }}
</a>
```

**`resources/views/components/dropdown-button.blade.php`:**

```blade
@props([
    'disabled' => false,
])

@php
$classes = 'dropdown__item';
if ($disabled) $classes .= ' dropdown__item--disabled';
@endphp

<button type="button" {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled) disabled @endif>
    {{ $slot }}
</button>
```

### Usage

```blade
{{-- User menu in nav --}}
<x-dropdown placement="right">
    <x-slot:trigger>{{ auth()->user()->name }}</x-slot:trigger>

    <x-dropdown-link href="/profile">Profile</x-dropdown-link>
    <x-dropdown-link href="/settings">Settings</x-dropdown-link>

    <div class="dropdown__divider"></div>

    <x-dropdown-link href="/logout">Logout</x-dropdown-link>
</x-dropdown>

{{-- Action menu on table row --}}
<x-dropdown placement="right">
    <x-slot:trigger>
        <x-button variant="secondary" size="sm">Actions</x-button>
    </x-slot:trigger>

    <x-dropdown-link href="{{ route('books.edit', $book) }}">Edit</x-dropdown-link>
    <x-dropdown-button>Duplicate</x-dropdown-button>

    <div class="dropdown__divider"></div>

    <x-dropdown-link href="{{ route('books.delete', $book) }}" class="dropdown__item--danger">
        Delete
    </x-dropdown-link>
</x-dropdown>
```

## Composition Rules

| Can contain (dropdown) | Can contain (menu) |
|---|---|
| Trigger slot (any content: button, text, icon) | dropdown-link items |
| Menu slot (dropdown items only) | dropdown-button items |
| — | dropdown__divider |

| Cannot contain | Cannot be inside |
|---|---|
| Forms or form inputs | Another dropdown (nested not supported) |
| Cards or block-level components | Nav (use Dropdown in the `end` slot) |
| Nested dropdowns | Button (trigger is separate) |

**Alpine behavior (built into component):**
- Click toggle to open/close
- `@click.outside` closes the menu
- Escape key closes
- `aria-expanded` toggles with state
- `x-cloak` prevents flash of unmounted menu
- No consumer Alpine needed for basic toggle behavior

## Accessibility

- Toggle has `role="button"`, `tabindex="0"`, `aria-haspopup="true"`, `aria-expanded`
- Menu items are `<a>` (navigation) or `<button>` (action) — focusable and keyboard-activatable
- Disabled items get `aria-disabled="true"` and `tabindex="-1"`
- Menu appears below the trigger; `placement="right"` aligns to right edge for nav menus

## Acceptance Criteria

1. `_dropdown.scss` compiles without errors
2. Toggle renders with caret indicator (`::after` triangle)
3. Caret rotates 180deg when `dropdown--open` class is present
4. Menu is hidden by default, visible when `dropdown--open` is present
5. `placement="right"` aligns menu to the right edge of trigger
6. `placement="center"` centers menu relative to trigger
7. Dropdown link renders as `<a>` with hover/active states
8. Dropdown button renders as `<button>`
9. `disabled` prop adds reduced opacity and `pointer-events: none`
10. Divider renders as a 1px horizontal line
11. Alpine `x-show` toggles menu; `@click.outside` closes it
