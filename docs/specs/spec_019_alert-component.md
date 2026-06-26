# Spec 019: Alert Component

**Status:** Approved

**References:** ADR-0020, Spec 018

## Objective

Build the Alert component — a dismissible, varianted message bar for success, error, warning, and info states. Used for flash messages, validation summaries, and status notifications.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_alert.scss`
- Blade component: `resources/views/components/alert.blade.php`
- Four variants: `success`, `danger`, `warning`, `info`
- Optional dismiss button
- Icon slot (default icon per variant)
- Message slot
- Toast mode: fixed-position floating alert with four corner positions
- Elevated shadow for toast mode

## Interfaces

### SCSS: `resources/sass/components/_alert.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/borders' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/shadows' as *;
@use '../tokens/transitions' as *;

.alert {
    display: flex;
    align-items: flex-start;
    gap: $space-sm;
    padding: $space-md;
    border-radius: $radius-sm;
    font-size: $font-size-sm;
    line-height: $line-height-base;
    border: $border-width-thin solid;

    &--success {
        background: rgba($color-success, 0.1);
        border-color: $color-success;
        color: darken($color-success, 15%);
    }

    &--danger {
        background: rgba($color-danger, 0.1);
        border-color: $color-danger;
        color: darken($color-danger, 15%);
    }

    &--warning {
        background: rgba($color-warning, 0.1);
        border-color: $color-warning;
        color: darken($color-warning, 15%);
    }

    &--info {
        background: rgba($color-info, 0.1);
        border-color: $color-info;
        color: darken($color-info, 15%);
    }

    // Toast mode — fixed-position floating alert
    &--toast {
        position: fixed;
        z-index: $z-dropdown;
        width: 360px;
        max-width: calc(100vw - $space-md * 2);
        box-shadow: $shadow-lg;

        // Corner positions
        &.alert--top-right {
            top: $space-md;
            right: $space-md;
        }

        &.alert--top-left {
            top: $space-md;
            left: $space-md;
        }

        &.alert--bottom-right {
            bottom: $space-md;
            right: $space-md;
        }

        &.alert--bottom-left {
            bottom: $space-md;
            left: $space-md;
        }
    }

    &__icon {
        flex-shrink: 0;
        width: 1.25em;
        height: 1.25em;
        margin-top: 1px;
    }

    &__body {
        flex: 1;
    }

    &__dismiss {
        flex-shrink: 0;
        background: none;
        border: none;
        cursor: pointer;
        padding: 2px;
        color: inherit;
        opacity: 0.7;
        transition: opacity $transition-fast;

        &:hover {
            opacity: 1;
        }
    }
}
```

### Blade: `resources/views/components/alert.blade.php`

```blade
@props([
    'variant' => 'info',
    'dismissible' => false,
    'toast' => false,
    'position' => 'top-right',  // top-right | top-left | bottom-right | bottom-left
])

@php
$variantClass = match($variant) {
    'success' => 'alert--success',
    'danger' => 'alert--danger',
    'warning' => 'alert--warning',
    default => 'alert--info',
};
$classes = "alert {$variantClass}";
if ($toast) {
    $classes .= ' alert--toast';
    $classes .= match($position) {
        'top-left' => ' alert--top-left',
        'bottom-right' => ' alert--bottom-right',
        'bottom-left' => ' alert--bottom-left',
        default => ' alert--top-right',
    };
}
@endphp

<div role="alert" {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon ?? true)
        <div class="alert__icon" aria-hidden="true">
            {{-- Icon per variant, e.g., check-circle, exclamation-triangle, info-circle --}}
        </div>
    @endif

    <div class="alert__body">
        {{ $slot }}
    </div>

    @if ($dismissible)
        <button type="button" class="alert__dismiss" aria-label="Dismiss alert">
            &times;
        </button>
    @endif
</div>
```

### Usage

```blade
{{-- Default info --}}
<x-alert>
    Book saved successfully.
</x-alert>

{{-- With variant --}}
<x-alert variant="danger">
    Please fix the errors below.
</x-alert>

{{-- Dismissible --}}
<x-alert variant="warning" dismissible>
    This shelf contains books. Delete them first.
</x-alert>

{{-- Toast — fixed position top-right (default) --}}
<x-alert toast variant="success" dismissible>
    Book saved successfully.
</x-alert>

{{-- Toast — bottom-left --}}
<x-alert toast position="bottom-left" variant="danger">
    Connection lost.
</x-alert>

{{-- Stacked toasts — consumer wraps in a container --}}
{{-- Consumer handles: Alpine x-show, x-transition, z-index stacking --}}
<div style="position: fixed; top: 1rem; right: 1rem; display: flex; flex-direction: column; gap: 0.5rem; z-index: 500;">
    <x-alert toast variant="info" dismissible>First toast</x-alert>
    <x-alert toast variant="success" dismissible>Second toast</x-alert>
</div>
```

## Composition Rules

| Can contain | Can be inside |
|---|---|
| Text content | Any container (card body, modal body, layout wrapper) |
| Inline links (styled as `alert__link`) | Card body |
| Icons (via `alert__icon` slot) | — |

| Cannot contain | Cannot be inside |
|---|---|
| Buttons (use card footer instead) | Another alert |
| Form inputs | Dropdown menu |
| Nested alerts | Button (unless as a toast — rare) |
| Cards or other components | Nav |

**Toast-specific rules:**
- Toast alerts are fixed-position by nature — place them directly in the `<body>` or a dedicated toast container
- For stacking multiple toasts, consumer must wrap in a positioned container with `display: flex; flex-direction: column; gap`
- Toast `position` only applies when `toast` is `true`; ignored for inline alerts
- Animations (slide in, fade) are handled by Alpine `x-transition` on the consumer — component provides no animation

## Accessibility

- `role="alert"` on the container — screen readers announce toast content immediately
- Dismiss button has `aria-label="Dismiss alert"`
- Dismissible alerts should use Alpine.js `x-show` + `x-transition` on the parent wrapper, not the component itself
- Color is not the only indicator — icon provides semantic cue
- Toasts must not trap focus (they're not dialogs). Users can tab past them.

## Acceptance Criteria

1. `_alert.scss` compiles without errors via `npm run build`
2. All four variant classes render with correct colors
3. Dismiss button renders when `dismissible` prop is true
4. Dismiss button is hidden when `dismissible` prop is false or absent
5. `role="alert"` is present on the rendered element
6. No visual regression — verify against existing Bootstrap flash messages
7. `toast` prop adds `alert--toast` class and fixed positioning
8. `position` prop applies correct corner class (`alert--top-right`, etc.)
9. When `toast` is false, `position` prop has no effect (no positioning classes added)
10. Toast variant includes `box-shadow: $shadow-lg`
