# Spec 020: Badge Component

**Status:** Approved

**References:** ADR-0020, Spec 018

## Objective

Build the Badge component — a small label for status indicators, counts, and tags. Used for location types, progress statuses, and metadata pills.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_badge.scss`
- Blade component: `resources/views/components/badge.blade.php`
- Four variants: `default`, `success`, `warning`, `danger`
- Two sizes: `sm`, `md` (default)
- Clickable variant: hover state, focus ring, cursor pointer
- Togglable clickable variant: `active` state for selected/unselected
- Slot for label content

### Out of Scope

## Interfaces

### SCSS: `resources/sass/components/_badge.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/transitions' as *;

.badge {
    display: inline-flex;
    align-items: center;
    font-size: $font-size-xs;
    font-weight: $font-weight-medium;
    line-height: 1;
    border-radius: $radius-full;
    white-space: nowrap;

    &--sm {
        padding: 2px $space-sm;
    }

    &--md {
        padding: 4px $space-sm;
    }

    &--default {
        background: $color-bg-alt;
        color: $color-text-muted;
    }

    &--success {
        background: rgba($color-success, 0.15);
        color: darken($color-success, 15%);
    }

    &--warning {
        background: rgba($color-warning, 0.15);
        color: darken($color-warning, 15%);
    }

    &--danger {
        background: rgba($color-danger, 0.15);
        color: darken($color-danger, 15%);
    }

    // Clickable
    &--clickable {
        cursor: pointer;
        transition: background $transition-fast, box-shadow $transition-fast;

        &:hover {
            filter: brightness(0.92);
        }

        &:focus-visible {
            outline: none;
            box-shadow: 0 0 0 2px $color-white,
                        0 0 0 4px $color-primary;
        }
    }

    // Togglable (clickable + active state)
    &--togglable {
        cursor: pointer;
        transition: background $transition-fast, box-shadow $transition-fast;

        &:focus-visible {
            outline: none;
            box-shadow: 0 0 0 2px $color-white,
                        0 0 0 4px $color-primary;
        }

        &.badge--active {
            filter: brightness(0.85);
            border: $border-width-thin solid currentColor;
        }
    }
}
```

### Blade: `resources/views/components/badge.blade.php`

```blade
@props([
    'variant' => 'default',
    'size' => 'md',
    'clickable' => false,
    'togglable' => false,
    'active' => false,
])

@php
$variantClass = match($variant) {
    'success' => 'badge--success',
    'warning' => 'badge--warning',
    'danger' => 'badge--danger',
    default => 'badge--default',
};
$sizeClass = $size === 'sm' ? 'badge--sm' : 'badge--md';
$classes = "badge {$variantClass} {$sizeClass}";
if ($togglable) {
    $classes .= ' badge--togglable';
    if ($active) $classes .= ' badge--active';
} elseif ($clickable) {
    $classes .= ' badge--clickable';
}
@endphp

@if ($togglable || $clickable)
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@else
    <span {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </span>
@endif
```

### Usage

```blade
{{-- Default badge --}}
<x-badge>Paperback</x-badge>

{{-- Status badges --}}
<x-badge variant="success">Available</x-badge>
<x-badge variant="warning">On Loan</x-badge>
<x-badge variant="danger">Overdue</x-badge>

{{-- Small badge --}}
<x-badge size="sm">3</x-badge>

{{-- Clickable badge (acts as button) --}}
<x-badge clickable variant="primary" wire:click="filter('fantasy')">
    Fantasy
</x-badge>

{{-- Togglable badge with Alpine --}}
<x-badge togglable x-on:click="active = !active" :active="$active">
    Science Fiction
</x-badge>
```

## Composition Rules

| Can contain | Can be inside |
|---|---|
| Text (short, 1-3 words) | Card header/footer |
| Numbers / counts | Table cells |
| Inline icons | List items |
| — | Button (as a label alongside text) |
| — | Heading / text inline |

| Cannot contain | Cannot be inside |
|---|---|
| Buttons | Another badge |
| Links | Form inputs |
| Form elements | Alert body (use inline text instead) |
| Block elements | |

## Accessibility

- Non-interactive badges render as `<span>` — no implicit role
- Clickable/togglable badges render as `<button>` — interactive by default, focusable, keyboard activatable
- Togglable badges without `.badge--active` are not selected; `aria-pressed` can be set via `$attributes` by the consumer
- Focus ring uses `:focus-visible` so it only shows for keyboard users
- If a static badge conveys status, ensure parent context makes meaning clear (don't rely on color alone)

## Acceptance Criteria

1. `_badge.scss` compiles without errors
2. All four variant classes render with correct colors
3. Both size classes (`--sm`, `--md`) render with correct padding
4. Component accepts arbitrary additional attributes via `$attributes`
5. Empty slot renders an empty badge (no visible text)
6. `clickable` prop renders a `<button>` with `.badge--clickable`, hover filter, and focus-visible ring
7. `togglable` prop renders a `<button>` with `.badge--togglable`; `active` prop adds `.badge--active`
