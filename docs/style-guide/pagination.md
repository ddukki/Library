# Pagination

## Purpose

Navigable page list for browsable result sets (book lists, author lists, tables with many rows). Wraps Laravel's `LengthAwarePaginator` to render page links with custom styling.

## Variants

| Size | Prop | Description |
|------|------|-------------|
| Medium | `size="md"` | Default |
| Small | `size="sm"` | Compact pages |

## Do

```blade
<x-pagination :paginator="$books" />

<x-pagination :paginator="$authors" size="sm" />

<div class="flex flex--center-between">
    <p>Showing {{ $books->firstItem() }}–{{ $books->lastItem() }} of {{ $books->total() }}</p>
    <x-pagination :paginator="$books" />
</div>
```

## Don't

```blade
{{-- Don't put pagination inside other interactive components --}}
<x-dropdown>
    <x-pagination :paginator="$books" />
</x-dropdown>
```

## Composition Rules

| Can contain | Can be inside |
|-------------|---------------|
| `<li>` items (prev, page numbers, next) | Above/below table |
| `<a>` links with `pagination__link` class | Card footer |

| Cannot contain | Cannot be inside |
|----------------|------------------|
| Buttons, forms, interactive elements | Another pagination |
| Dropdowns | Form |

## Accessibility

- Wrapped in `<nav>` with `aria-label="Pagination"`
- Page links have `aria-label="Page N"`
- Previous/Next have `aria-label="Previous page"` / `aria-label="Next page"`
- Active page has `aria-current="page"`
- Disabled prev/next have `aria-disabled="true"` and `tabindex="-1"`
- Ellipsis uses `<span>` with `aria-hidden="true"` — not focusable

## Responsive Behavior

Static — no responsive changes. Use `size="sm"` for compact layouts.
