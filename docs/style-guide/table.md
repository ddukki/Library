# Table

## Purpose

Styled data table for listing records (books, authors, editions, shelves). Supports striped rows, sortable headers, responsive horizontal scroll, empty state, and compact variant.

## Variants

| Variant | Prop | Description |
|---------|------|-------------|
| Striped | `striped` | Alternating row backgrounds |
| Hover | `hover` | Row highlight on hover |
| Compact | `compact` | Reduced cell padding |
| Sticky Header | `sticky-header` | Header pinned on vertical scroll |
| Fixed Column | `fixed-column` | Sticky left column on horizontal scroll |

### Sortable Headers

Add classes to `<th>` elements:

| Class | Description |
|-------|-------------|
| `table__th--sortable` | Pointer cursor, hover color |
| `table__th--sort-asc` | Ascending arrow indicator |
| `table__th--sort-desc` | Descending arrow indicator |

### Alignment

| Class | Description |
|-------|-------------|
| `table__th--center` / `table__td--center` | Center align |
| `table__th--right` / `table__td--right` | Right align |

## Do

```blade
<x-table striped hover>
    <x-slot:header>
        <th class="table__th">Title</th>
        <th class="table__th">Status</th>
    </x-slot:header>
    <x-slot:body>
        @foreach ($books as $book)
            <tr>
                <td class="table__td">{{ $book->title }}</td>
                <td class="table__td">
                    <x-badge variant="success">Available</x-badge>
                </td>
            </tr>
        @endforeach
    </x-slot:body>
</x-table>

<x-table empty="No editions found.">
    <x-slot:header>...</x-slot:header>
</x-table>
```

## Composition Rules

| Can contain (header) | Can contain (body) |
|----------------------|--------------------|
| `<th>` only | `<tr>` with `<td>` only |
| Sortable class modifiers | Badges, buttons, text |

| Cannot contain |
|----------------|
| Divs, forms, or block elements directly inside `<table>` |
| Nested tables |
| Interactive elements inside `<th>` without proper scoping |

## Accessibility

- Semantic `<table>`, `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>` elements
- Sortable `<th>` should have `aria-sort` set by consumer
- Empty state spans all columns with `colspan="99"`
- Responsive wrapper is a `<div>` that scrolls — no role needed

## Responsive Behavior

- Horizontal scroll on narrow viewports via `table-wrapper`
- Sticky header requires a container with fixed height / `max-height` and `overflow-y: auto`
