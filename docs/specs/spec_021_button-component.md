# Spec 021: Button Component

**Status:** Approved

**References:** ADR-0020, Spec 018

## Objective

Build the Button component — the primary interactive element for forms, actions, and navigation. Must cover all current Bootstrap button uses in the app.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_button.scss`
- Blade component: `resources/views/components/button.blade.php`
- Three visual variants: `primary`, `secondary`, `danger`
- Three sizes: `sm`, `md` (default), `lg`
- Link variant (appears as a link but is a button)
- Full-width modifier (`block`)
- Disabled state styling
- Loading state (CSS-only spinner overlay)
- Support for icon + text layout
- Toggle/active state styling (`active` prop, pressed visual)

### Out of Scope

- Dropdown toggle styling (caret, open state — handled by Dropdown component)

## Interfaces

### SCSS: `resources/sass/components/_button.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/transitions' as *;

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: $space-sm;
    font-family: $font-family-body;
    font-weight: $font-weight-medium;
    line-height: $line-height-base;
    text-align: center;
    text-decoration: none;
    border: $border-width-thin solid transparent;
    border-radius: $radius-sm;
    cursor: pointer;
    transition:
        background $transition-fast,
        border-color $transition-fast,
        color $transition-fast,
        box-shadow $transition-fast;

    &:disabled,
    &.btn--disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    // Sizes
    &--sm {
        padding: $space-xs $space-sm;
        font-size: $font-size-sm;
    }

    &--md {
        padding: $space-sm $space-md;
        font-size: $font-size-base;
    }

    &--lg {
        padding: $space-md $space-lg;
        font-size: $font-size-lg;
    }

    // Variants
    &--primary {
        background: $color-primary;
        border-color: $color-primary;
        color: $color-text-inverse;

        &:hover {
            background: $color-primary-light;
            border-color: $color-primary-light;
        }

        &:active {
            background: $color-primary-dark;
            border-color: $color-primary-dark;
        }
    }

    &--secondary {
        background: transparent;
        border-color: $color-border;
        color: $color-text;

        &:hover {
            background: $color-bg-alt;
            border-color: $color-border;
        }

        &:active {
            background: darken($color-bg-alt, 5%);
        }
    }

    &--danger {
        background: $color-danger;
        border-color: $color-danger;
        color: $color-text-inverse;

        &:hover {
            background: darken($color-danger, 8%);
            border-color: darken($color-danger, 8%);
        }

        &:active {
            background: darken($color-danger, 15%);
        }
    }

    // Active/toggled state
    &.btn--active {
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    &--primary.btn--active {
        background: $color-primary-dark;
        border-color: $color-primary-dark;
    }

    &--secondary.btn--active {
        background: darken($color-bg-alt, 10%);
        border-color: darken($color-border, 10%);
    }

    &--danger.btn--active {
        background: darken($color-danger, 12%);
        border-color: darken($color-danger, 12%);
    }

    &--link.btn--active {
        color: $color-primary-dark;
    }

    // Link variant
    &--link {
        background: none;
        border: none;
        color: $color-primary;
        padding: 0;
        text-decoration: underline;
        display: inline;

        &:hover {
            color: $color-primary-light;
        }
    }

    // Modifiers
    &--block {
        width: 100%;
    }

    &__icon {
        flex-shrink: 0;
        width: 1em;
        height: 1em;
    }
}
```

### Blade: `resources/views/components/button.blade.php`

```blade
@props([
    'variant' => 'primary',
    'size' => 'md',
    'block' => false,
    'disabled' => false,
    'active' => false,
    'type' => 'button',
    'href' => null,
    'loading' => false,
])

@php
$variantClass = match($variant) {
    'secondary' => 'btn--secondary',
    'danger' => 'btn--danger',
    'link' => 'btn--link',
    default => 'btn--primary',
};
$sizeClass = $size === 'sm' ? 'btn--sm' : ($size === 'lg' ? 'btn--lg' : 'btn--md');
$classes = "btn {$variantClass} {$sizeClass}";

if ($block) {
    $classes .= ' btn--block';
}
if ($active) {
    $classes .= ' btn--active';
}
if ($disabled) {
    $classes .= ' btn--disabled';
}
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes, 'role' => 'button']) }}
        @if($disabled) aria-disabled="true" tabindex="-1" @endif
        @if($active) aria-current="page" @endif>
        @if ($icon ?? null)
            <span class="btn__icon" aria-hidden="true">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled) disabled @endif
        @if($active) aria-pressed="true" @endif>
        @if ($icon ?? null)
            <span class="btn__icon" aria-hidden="true">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </button>
@endif
```

### Usage

```blade
{{-- Primary button --}}
<x-button>Save</x-button>

{{-- Secondary button --}}
<x-button variant="secondary">Cancel</x-button>

{{-- Danger button --}}
<x-button variant="danger" size="sm">Delete</x-button>

{{-- Link as button --}}
<x-button variant="link">Edit</x-button>

{{-- Full-width block --}}
<x-button block>Submit</x-button>

{{-- With icon --}}
<x-button icon="...">Add Author</x-button>

{{-- Toggled/active --}}
<x-button :active="$filter === 'all'" wire:click="$set('filter', 'all')">All</x-button>
<x-button :active="$filter === 'fiction'" secondary wire:click="$set('filter', 'fiction')">Fiction</x-button>

{{-- As link --}}
<x-button href="{{ route('authors.index') }}">Back to Authors</x-button>
```

## Composition Rules

| Can contain | Can be inside |
|---|---|
| Text (short action label) | Card footer |
| Icon (via `icon` prop) | Form (as submit) |
| Badge (count indicator, sm only) | Alert (dismiss action) |
| — | Modal footer |
| — | Nav |
| — | Table row actions |
| — | Any container (card body, list item, flex layout) |

| Cannot contain | Cannot be inside |
|---|---|
| Another button | Another button |
| Form inputs | Badge (inside a badge) |
| Cards, modals, or block components | Heading text |
| Dropdown menus (use Dropdown component) | |

## Accessibility

- `<button>` elements get `disabled` attribute when disabled
- `<a>` elements get `aria-disabled="true"` and `tabindex="-1"` when disabled
- Active `<button>` gets `aria-pressed="true"`
- Active `<a>` gets `aria-current="page"`
- Loading state adds `aria-busy="true"` — use Alpine to toggle
- Icon-only buttons must have aria-label on the wrapping `x-button`
- Focus styles should use `:focus-visible` (not `:focus`) to show outline only for keyboard users

## Acceptance Criteria

1. `_button.scss` compiles without errors
2. All three variant classes render with correct hover/active states
3. All three size classes render with correct padding and font sizes
4. Disabled button renders with reduced opacity and `pointer-events: none`
5. Link variant renders as an inline, underlined element with no padding
6. Block modifier makes the button full-width
7. Blade component renders `<button>` or `<a>` based on `href` prop presence
8. Disabled `<a>` gets `aria-disabled="true"` instead of `disabled`
9. `active` prop adds `btn--active` class with inset box-shadow and variant-specific darker background
10. Active `<button>` renders `aria-pressed="true"`
11. Active `<a>` renders `aria-current="page"`
