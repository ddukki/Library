# Spec 024: List Component

**Status:** Approved

**References:** ADR-0020, Spec 018

## Objective

Build the List component — styled unordered and ordered lists for displaying sets of items. Used for author lists, location type lists, book lists, and metadata groupings.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_list.scss`
- Blade component: `resources/views/components/list.blade.php`
- Two types: unordered (`ul`) and ordered (`ol`)
- Two styles: `bare` (no bullets/numbers, used for action lists) and `divided` (items separated by borders)
- List item component: `resources/views/components/list-item.blade.php`
- Inline variant (horizontal list)
- Draggable variant: drag handle styling, dragging state (elevated, slightly rotated), drop target indicator line

### Out of Scope

## Interfaces

### SCSS: `resources/sass/components/_list.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/shadows' as *;
@use '../tokens/transitions' as *;

.list {
    display: flex;
    flex-direction: column;
    gap: 0;
    margin: 0;
    padding: 0;
    list-style: none;

    &--ordered {
        list-style: decimal;
        padding-left: $space-lg;

        .list__item {
            display: list-item;
        }
    }

    &--bare {
        .list__item {
            padding: 0;
            border: none;
        }
    }

    &--divided {
        .list__item {
            padding: $space-sm 0;
            border-bottom: $border-width-thin solid $color-border;

            &:last-child {
                border-bottom: none;
            }
        }
    }

    &--inline {
        flex-direction: row;
        flex-wrap: wrap;
        gap: $space-sm;

        .list__item {
            padding: 0;
            border: none;

            &:not(:last-child)::after {
                content: '·';
                margin-left: $space-sm;
                color: $color-text-muted;
            }
        }
    }

    // Draggable — items show a grab handle and dragging states
    &--draggable {
        .list__item {
            cursor: grab;
            user-select: none;

            &:active {
                cursor: grabbing;
            }
        }
    }

    &__item {
        font-size: $font-size-base;
        line-height: $line-height-base;
        color: $color-text;

        // Drag handle (6-dot grip icon via CSS)
        .list__handle {
            display: inline-flex;
            align-items: center;
            padding: 2px;
            margin-right: $space-sm;
            cursor: grab;
            color: $color-text-muted;
            opacity: 0.5;
            transition: opacity $transition-fast;

            // Three-line grip pattern
            &::before {
                content: '⋮⋮';
                letter-spacing: -2px;
                font-size: $font-size-lg;
                line-height: 1;
            }

            &:hover {
                opacity: 1;
            }
        }

        // Actively being dragged
        &.list__item--dragging {
            opacity: 0.5;
            background: $color-bg-alt;
            box-shadow: $shadow-md;
        }

        // Drop target indicator
        &.list__item--drag-over {
            border-top: 2px solid $color-primary;
        }

        // Ghost placeholder (original position while dragging)
        &.list__item--drag-ghost {
            opacity: 0.3;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 4px,
                rgba($color-primary, 0.05) 4px,
                rgba($color-primary, 0.05) 8px
            );
        }
    }
}
```

### Blade Components

**`resources/views/components/list.blade.php`:**

```blade
@props([
    'type' => 'unordered', // unordered | ordered
    'style' => 'bare',     // bare | divided | inline
    'draggable' => false,
])

@php
$tag = $type === 'ordered' ? 'ol' : 'ul';
$classes = 'list';
if ($type === 'ordered') $classes .= ' list--ordered';
if ($style === 'divided') $classes .= ' list--divided';
if ($style === 'inline') $classes .= ' list--inline';
if ($draggable) $classes .= ' list--draggable';
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
```

**`resources/views/components/list-item.blade.php`:**

```blade
@props([
    'handle' => false,
])

<li {{ $attributes->merge(['class' => 'list__item']) }}>
    @if ($handle)
        <span class="list__handle" aria-hidden="true"></span>
    @endif
    {{ $slot }}
</li>
```

### Usage

```blade
{{-- Bare list (default) --}}
<x-list>
    <x-list-item>Item 1</x-list-item>
    <x-list-item>Item 2</x-list-item>
</x-list>

{{-- Divided list --}}
<x-list style="divided">
    <x-list-item>Author: Tolkien</x-list-item>
    <x-list-item>Author: Pullman</x-list-item>
</x-list>

{{-- Inline list --}}
<x-list style="inline">
    <x-list-item>Fantasy</x-list-item>
    <x-list-item>Science Fiction</x-list-item>
    <x-list-item>History</x-list-item>
</x-list>

{{-- Ordered list --}}
<x-list type="ordered">
    @foreach ($books as $book)
        <x-list-item>{{ $book->title }}</x-list-item>
    @endforeach
</x-list>

{{-- Draggable list with Alpine Sort --}}
<x-list style="divided" draggable x-data="sortable()" x-sort>
    @foreach ($shelves as $shelf)
        <x-list-item handle x-sort:item="{{ $shelf->id }}"
            :class="{ 'list__item--dragging': $el.classList.contains('opacity-50') }">
            {{ $shelf->name }}
        </x-list-item>
    @endforeach
</x-list>
```

## Composition Rules

| Can contain (list) | Can contain (list item) |
|---|---|
| List items only | Text |
| — | Inline elements (badge, link) |
| — | Form inputs (checkbox list) |
| — | Button (action item) |
| — | Card (nested card list — rare) |

| Cannot contain | Cannot be inside |
|---|---|
| Divs, headings, or block elements directly | Another list (use nested `<x-list>` inside `<x-list-item>`) |
| Buttons, forms, or interactive content directly | Modal body |
| — | Table cell (use inline list only) |

**Draggable-specific rules:**
- `handle` prop on a list item renders a grip icon (`list__handle`) before the slot content
- Drag behavior (SortableJS, Alpine Sort) is handled by the consumer via JS attributes
- SCSS provides visual states only: `list__item--dragging`, `list__item--drag-over`, `list__item--drag-ghost`
- Draggable list items use `cursor: grab` (idle) and `cursor: grabbing` (active)

## Accessibility

- Draggable lists must have `role="listbox"` and items `role="option"` for keyboard reordering
- Drag handle has `aria-hidden="true"` (decorative) — keyboard reorder behavior is handled by Alpine/Livewire on the consumer
- Grab cursor is provided on `.list__item` in draggable mode; `cursor: grabbing` on `:active`

## Acceptance Criteria

1. `_list.scss` compiles without errors
2. `list--bare` renders with no bullets/markers and no item borders
3. `list--divided` renders items separated by bottom borders
4. `list--inline` renders items horizontally separated by dots
5. Ordered list (`type="ordered"`) renders with numbered markers
6. List item renders as `<li>` element
7. Empty list renders no visible content (no extraneous markers)
8. `list--draggable` class adds `cursor: grab` to list items
9. `handle` prop on list item renders a `.list__handle` element with grip icon
10. `.list__item--dragging` renders with reduced opacity and shadow
11. `.list__item--drag-over` renders with a top border indicator
12. `.list__item--drag-ghost` renders with striped background and low opacity
