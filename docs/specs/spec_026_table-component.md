# Spec 026: Table Component

**Status:** Approved

**References:** ADR-0020, Spec 018

## Objective

Build the Table component — a styled data table for listing records (books, authors, editions, shelves). Must support striped rows, sortable headers, responsive horizontal scroll, empty state, and compact variant for dense data.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_table.scss`
- Blade component: `resources/views/components/table.blade.php`
- Responsive wrapper (horizontal scroll on mobile)
- Striped row variant
- Hover highlight variant
- Compact variant (reduced padding)
- Sortable column headers with ascending/descending indicators
- Column alignment modifiers (left, center, right)
- Empty state message
- Sticky headers (header row stays visible on vertical scroll)

### Out of Scope

- Table pagination (handled by Pagination component)
- Inline table editing (consumer handles via Alpine)
- Row selection / checkboxes (consumer adds manually)
- Table filtering (consumer handles via Alpine/forms)
- Fixed columns (sticky left column via CSS `position: sticky`)

## Interfaces

### SCSS: `resources/sass/components/_table.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/transitions' as *;
@use '../primitives/responsive' as *;

.table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: $font-size-sm;
    line-height: $line-height-base;

    // Striped rows
    &--striped {
        tbody tr:nth-child(even) {
            background: $color-bg;
        }
    }

    // Hover highlight
    &--hover {
        tbody tr {
            transition: background $transition-fast;

            &:hover {
                background: $color-bg-alt;
            }
        }
    }

    // Compact (reduced padding for dense data)
    &--compact {
        th, td {
            padding: $space-xs $space-sm;
        }
    }

    // Sortable header
    &__th {
        padding: $space-sm $space-md;
        font-weight: $font-weight-bold;
        font-size: $font-size-xs;
        text-transform: uppercase;
        letter-spacing: $letter-spacing-wide;
        color: $color-text-muted;
        text-align: left;
        white-space: nowrap;
        border-bottom: 2px solid $color-border;

        &--sortable {
            cursor: pointer;
            user-select: none;
            transition: color $transition-fast;

            &:hover {
                color: $color-primary;
            }
        }

        &--sort-asc::after {
            content: ' ↑';
            font-size: $font-size-xs;
        }

        &--sort-desc::after {
            content: ' ↓';
            font-size: $font-size-xs;
        }
    }

    // Cell
    &__td {
        padding: $space-sm $space-md;
        border-bottom: $border-width-thin solid $color-border;
        vertical-align: middle;
    }

    // Alignment modifiers
    &__th--center,
    &__td--center {
        text-align: center;
    }

    &__th--right,
    &__td--right {
        text-align: right;
    }

    // Fixed column (sticky left)
    &--fixed-col {
        .table__th--fixed,
        .table__td--fixed {
            position: sticky;
            left: 0;
            z-index: 2;
            background: inherit;

            &::after {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                width: 4px;
                background: linear-gradient(to right, rgba($color-primary, 0.1), transparent);
                pointer-events: none;
            }
        }

        .table__th--fixed {
            z-index: 3; // Above table body cells
        }
    }

    // Sticky header
    &--sticky-header {
        thead {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        th {
            background: $color-white;
        }

        .table--striped & th {
            background: $color-white; // Override any striped background on header
        }
    }

    // Empty state
    &__empty {
        padding: $space-2xl $space-md;
        text-align: center;
        color: $color-text-muted;
        font-size: $font-size-base;
        border-bottom: none;

        td {
            border-bottom: none;
        }
    }
}
```

### Blade: `resources/views/components/table.blade.php`

```blade
@props([
    'striped' => false,
    'hover' => false,
    'compact' => false,
    'stickyHeader' => false,
    'fixedColumn' => false,
    'empty' => null,
])

@php
$classes = 'table';
if ($striped) $classes .= ' table--striped';
if ($hover) $classes .= ' table--hover';
if ($compact) $classes .= ' table--compact';
if ($stickyHeader) $classes .= ' table--sticky-header';
if ($fixedColumn) $classes .= ' table--fixed-col';
@endphp

<div class="table-wrapper">
    <table {{ $attributes->merge(['class' => $classes]) }}>
        @if (isset($header))
            <thead>
                <tr>
                    {{ $header }}
                </tr>
            </thead>
        @endif

        @if (isset($body))
            <tbody>
                {{ $body }}
            </tbody>
        @elseif ($empty)
            <tbody>
                <tr>
                    <td class="table__empty" colspan="99">
                        {{ $empty }}
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</div>
```

No separate Blade components for `th`/`td`/`tr` — the consumer writes semantic HTML. The SCSS classes are applied via manual attribute binding when needed.

### Usage

```blade
{{-- Basic table --}}
<x-table>
    <x-slot:header>
        <th class="table__th">Name</th>
        <th class="table__th">Author</th>
        <th class="table__th table__th--right">Actions</th>
    </x-slot:header>

    <x-slot:body>
        @foreach ($editions as $edition)
            <tr>
                <td class="table__td">{{ $edition->name }}</td>
                <td class="table__td">{{ $edition->author->name }}</td>
                <td class="table__td table__td--right">
                    <x-button variant="secondary" size="sm">Edit</x-button>
                </td>
            </tr>
        @endforeach
    </x-slot:body>
</x-table>

{{-- Striped, hover, compact --}}
<x-table striped hover compact>
    <x-slot:header>
        <th class="table__th">Title</th>
        <th class="table__th">Status</th>
    </x-slot:header>
    <x-slot:body>
        @foreach ($books as $book)
            <tr>
                <td class="table__td">{{ $book->title }}</td>
                <td class="table__td"><x-badge variant="success">Available</x-badge></td>
            </tr>
        @endforeach
    </x-slot:body>
</x-table>

{{-- Sortable columns with Alpine --}}
<x-table hover>
    <x-slot:header>
        <th class="table__th table__th--sortable {{ $sort === 'name' ? 'table__th--sort-' . $direction : '' }}"
            wire:click="sort('name')">Name</th>
        <th class="table__th table__th--sortable {{ $sort === 'author' ? 'table__th--sort-' . $direction : '' }}"
            wire:click="sort('author')">Author</th>
    </x-slot:header>
    <x-slot:body>...</x-slot:body>
</x-table>

{{-- Sticky headers --}}
<x-table sticky-header>
    <x-slot:header>
        <th class="table__th">Name</th>
        <th class="table__th">Author</th>
    </x-slot:header>
    <x-slot:body>...</x-slot:body>
</x-table>

{{-- Fixed column — first column stays visible on horizontal scroll --}}
<x-table fixed-column>
    <x-slot:header>
        <th class="table__th table__th--fixed">Name</th>
        <th class="table__th">Author</th>
        <th class="table__th">Location Type</th>
        <th class="table__th">Shelves</th>
        <th class="table__th table__th--right">Actions</th>
    </x-slot:header>
    <x-slot:body>
        @foreach ($editions as $edition)
            <tr>
                <td class="table__td table__td--fixed">{{ $edition->name }}</td>
                <td class="table__td">{{ $edition->author->name }}</td>
                <td class="table__td">{{ $edition->locationType->name }}</td>
                <td class="table__td">...</td>
                <td class="table__td table__td--right">
                    <x-button variant="secondary" size="sm">Edit</x-button>
                </td>
            </tr>
        @endforeach
    </x-slot:body>
</x-table>

{{-- Empty state --}}
<x-table empty="No editions found.">
    <x-slot:header>...</x-slot:header>
</x-table>
```

## Composition Rules

| Can contain (header) | Can contain (body) | Can contain (empty) |
|---|---|---|
| `<th class="table__th">` only | `<tr>` with `<td class="table__td">` only | Text message |
| Sortable class modifiers | Badges, buttons, text | — |

| Cannot contain | Cannot be inside |
|---|---|
| Divs, forms, or block elements directly inside `<table>` | Card header |
| Nested tables | Alert body |
| Interactive elements inside `<th>` without proper scoping | Dropdown menu |

**Fixed column usage:**
- Consumer adds `table__th--fixed` / `table__td--fixed` classes to the leftmost column cells
- Only the first (leftmost) column should be fixed — multiple fixed columns require manual z-index management
- `&::after` pseudo-element draws a subtle shadow gradient on the right edge as a visual separator
- Works alongside sticky headers — the fixed `<th>` has higher z-index than fixed `<td>` for proper layering

**Sticky header usage:**
- `sticky-header` prop pins the `<thead>` to the top of the scrolling container
- The table must be inside a container with a fixed height or `max-height` and `overflow-y: auto` for the sticky behavior to work
- Works with striped and hover variants — header background stays solid white regardless of stripe pattern

**Sortable header usage:**
- Add `table__th--sortable` class to make the header visibly clickable
- Add `table__th--sort-asc` or `table__th--sort-desc` for active sort direction indicator
- The sort behavior (click handler, sort logic) is handled by the consumer (Alpine, Livewire, or backend)
- The component provides visuals only

## Accessibility

- Semantic `<table>`, `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>` elements
- Sortable `<th>` should have `aria-sort="ascending"` or `aria-sort="descending"` set by the consumer
- Empty state spans all columns with `colspan="99"` for proper table structure
- Responsive wrapper is a `<div>` that scrolls — no role needed

## Acceptance Criteria

1. `_table.scss` compiles without errors
2. Basic table renders with correct border, padding, and typography
3. `table--striped` alternates row background colors
4. `table--hover` highlights row on hover
5. `table--compact` reduces cell padding
6. `table__th--sortable` shows pointer cursor and hover color
7. `table__th--sort-asc` and `table__th--sort-desc` show sort arrow
8. `table__th--center` / `table__td--center` center-aligns content
9. `table__th--right` / `table__td--right` right-aligns content
10. `empty` prop renders a centered message spanning all columns
11. Table wrapper scrolls horizontally on narrow viewports
12. `sticky-header` prop pins `<thead>` with `position: sticky; top: 0`
13. Sticky header `<th>` has solid white background, unaffected by striped variant
14. `fixed-column` prop enables fixed column classes
15. `.table__th--fixed` and `.table__td--fixed` have `position: sticky; left: 0`
16. Fixed cells show shadow gradient on right edge via `::after` pseudo-element
17. Fixed `<th>` has higher z-index than fixed `<td>` for proper thead > tbody layering
