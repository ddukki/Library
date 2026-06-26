# Spec 028: Modal Component

**Status:** Approved

**References:** ADR-0020, Spec 018, Spec 022 (Form Input — forms inside modals)

## Objective

Build the Modal component — an overlay dialog for confirmations, forms, and detailed content that requires user attention without navigating away from the current page.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_modal.scss`
- Blade component: `resources/views/components/modal.blade.php`
- Overlay/backdrop with fade
- Centered panel with header/body/footer sections
- Four sizes: `sm`, `md` (default), `lg`, `xl`
- Close button (top-right X) in header
- Scrollable body (content taller than viewport)
- Closing methods: X button, Escape key, click-outside overlay
- Alpine `x-data` with open/close state
- `x-cloak` to prevent flash

### Out of Scope

- Nested modals (UX anti-pattern; stack only one deep at most)
- Confirm/alert shortcut components (`<x-modal.confirm>`) — consumer builds with modal + button
- Fullscreen modal (use a separate page instead)
- Transition/animation via Alpine `x-transition` (applied per project if desired)

## Interfaces

### SCSS: `resources/sass/components/_modal.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/shadows' as *;
@use '../tokens/transitions' as *;
@use '../tokens/zindex' as *;

// Backdrop
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: $z-modal-backdrop;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: $-space-lg;
}

// Panel
.modal {
    position: relative;
    background: $color-white;
    border-radius: $radius-lg;
    box-shadow: $shadow-xl;
    width: 100%;
    max-width: 32rem; // default md
    max-height: calc(100vh - $space-lg * 2);
    display: flex;
    flex-direction: column;
    z-index: $z-modal;
    animation: modal-enter $transition-normal ease-out;

    // Sizes
    &--sm { max-width: 24rem; }
    &--lg { max-width: 48rem; }
    &--xl { max-width: 64rem; }
}

@keyframes modal-enter {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

// Header
.modal__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: $-space-md $-space-lg;
    border-bottom: 1px solid $color-border;
    flex-shrink: 0;
}

.modal__title {
    font-size: $font-size-lg;
    font-weight: $font-weight-semibold;
    color: $color-text;
    margin: 0;
}

.modal__close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border: none;
    background: none;
    cursor: pointer;
    color: $color-text-muted;
    border-radius: $radius-sm;
    font-size: 1.25rem;
    line-height: 1;
    transition: background $transition-fast, color $transition-fast;

    &:hover {
        background: $color-bg-alt;
        color: $color-text;
    }

    &:focus-visible {
        outline: 2px solid $color-primary;
        outline-offset: 2px;
    }
}

// Body (scrollable)
.modal__body {
    padding: $space-lg;
    overflow-y: auto;
    flex: 1 1 auto;
}

// Footer
.modal__footer {
    display: flex;
    align-items: center;
    gap: $space-sm;
    padding: $space-md $space-lg;
    border-top: 1px solid $color-border;
    flex-shrink: 0;
    justify-content: flex-end;
}
```

### Blade Component

**`resources/views/components/modal.blade.php`:**

```blade
@props([
    'name' => 'modal',
    'size' => 'md',  // sm | md | lg | xl
    'title' => '',
    'show' => false,
])

@php
$sizeClass = match($size) {
    'sm' => 'modal--sm',
    'lg' => 'modal--lg',
    'xl' => 'modal--xl',
    default => '',
};
@endphp

<div x-data="{ open: {{ $show ? 'true' : 'false' }} }"
    x-cloak
    x-show="open"
    @keydown.escape.window="open = false"
    x-trap.noscroll="open"
    class="modal-backdrop"
    @click.self="open = false">

    <div class="modal {{ $sizeClass }}"
        role="dialog"
        aria-modal="true"
        :aria-labelledby="'modal-title-' + '{{ $name }}'"
        x-on:click.stop>

        {{-- Header --}}
        <div class="modal__header">
            <h2 class="modal__title"
                :id="'modal-title-' + '{{ $name }}'"
                x-text="'{{ $title }}'">
            </h2>
            <button type="button" class="modal__close"
                @click="open = false"
                aria-label="Close modal" title="Close">
                &times;
            </button>
        </div>

        {{-- Body --}}
        <div class="modal__body">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @if(isset($footer))
        <div class="modal__footer">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>
```

### Usage

```blade
{{-- Basic confirmation --}}
<x-modal name="confirm-delete" title="Delete Book" size="sm">
    Are you sure you want to delete <strong>{{ $book->title }}</strong>?

    <x-slot:footer>
        <x-button variant="secondary" @click="open = false">Cancel</x-button>
        <x-button variant="danger">Delete</x-button>
    </x-slot:footer>
</x-modal>

{{-- Form in modal --}}
<x-modal name="edit-book" title="Edit Book" size="lg">
    <form method="POST" action="{{ route('books.update', $book) }}">
        @csrf
        @method('PUT')
        <x-form-input name="title" label="Title" :value="$book->title" required />

        <x-slot:footer>
            <x-button variant="secondary" @click="open = false">Cancel</x-button>
            <x-button type="submit">Save</x-button>
        </x-slot:footer>
    </form>
</x-modal>

{{-- Opening the modal --}}
<button @click="$dispatch('open-modal', 'confirm-delete')">Delete</button>

{{-- Or by setting open directly if bound --}}
<x-button @click="$refs.confirmModal.open = true">Delete</x-button>
```

Note: Alpine `$dispatch` or direct binding on the modal's `x-data` scope opens the modal. The `@click.outside` and `@keydown.escape.window` close handlers are built in.

## Composition Rules

| Can contain (modal) | Can contain (footer slot) |
|---|---|
| Any content in `$slot` (body) | Button components |
| Footer slot (buttons) | Text, form actions |

| Cannot contain | Cannot be inside |
|---|---|
| Another modal | Another modal |
| Long unformatted text without scroll | Card (redundant layering) |

## Dependencies

Requires Alpine Focus plugin for `x-trap`:

```bash
npm install @alpinejs/focus
```

```js
// app.js or bootstrap
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
Alpine.plugin(focus)
```

## Accessibility

- `role="dialog"` and `aria-modal="true"` on the panel
- `aria-labelledby` links to the modal title `h2`
- Close button has `aria-label="Close modal"`
- Escape key closes via `@keydown.escape.window`
- Click-outside on backdrop closes via `@click.self` (only triggers on backdrop click, not panel click via `@click.stop`)
- **Focus trap** via Alpine `x-trap.noscroll` — Tab cycles within modal, body scroll is locked

## Acceptance Criteria

1. `_modal.scss` compiles without errors
2. Backdrop covers entire viewport with semi-transparent background
3. Panel is centered vertically and horizontally
4. Four sizes (`sm`/`md`/`lg`/`xl`) produce different max-widths
5. Header contains title and close X button
6. Body is scrollable when content overflows
7. Footer renders below a separator when `$footer` slot is provided
8. Modal is hidden by default (`x-cloak` prevents flash)
9. Close X button sets `open = false`
10. Escape key closes modal
11. Click outside the panel (on backdrop) closes modal
12. Click inside the panel does not close modal
13. `role="dialog"` and `aria-modal="true"` are present on the panel
14. `aria-labelledby` correctly references the title element ID
15. Focus is trapped inside modal while open (Tab cycles within)
16. Body scroll is locked while modal is open
