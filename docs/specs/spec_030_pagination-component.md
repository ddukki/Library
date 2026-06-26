# Spec 030: Pagination Component

**Status:** Approved

**References:** ADR-0020, Spec 018, Laravel paginator (`Illuminate\Pagination\LengthAwarePaginator`)

## Objective

Build the Pagination component — a navigable page list for browsable result sets (book lists, author lists, tables with many rows). Wraps Laravel's paginator to render page links with custom styling.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_pagination.scss`
- Blade component: `resources/views/components/pagination.blade.php`
- Laravel paginator integration (`$paginator->links()` pattern replaced)
- Previous / Next arrow buttons
- Page number links
- Active page indicator (filled/highlighted)
- Disabled state for boundary pages (prev on page 1, next on last page)
- Ellipsis (`...`) for large page ranges
- Two sizes: `md` (default), `sm`

### Out of Scope

- Compact variant (prev/next only, no page numbers) — use `paginator->simpleLinks()` if needed
- "Page X of Y" text label — consumer adds if desired
- Page size selector ("Show 10/25/50") — separate form control
- Infinite scroll — separate UX pattern
- `simplePaginate()` support — only `LengthAwarePaginator` (needs total pages for ellipsis)

## Interfaces

### SCSS: `resources/sass/components/_pagination.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/transitions' as *;

.pagination {
    display: flex;
    align-items: center;
    gap: 2px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.pagination__item {
    // Shared button/nav styles
}

.pagination__link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    height: 2.25rem;
    padding: 0 $space-sm;
    font-size: $font-size-sm;
    color: $color-text;
    text-decoration: none;
    border-radius: $radius-md;
    transition: background $transition-fast, color $transition-fast;

    &:hover {
        background: $color-bg-alt;
        color: $color-primary;
        text-decoration: none;
    }

    &:focus-visible {
        outline: 2px solid $color-primary;
        outline-offset: 2px;
    }

    // Active page
    &.pagination__link--active {
        background: $color-primary;
        color: $color-white;
        font-weight: $font-weight-semibold;

        &:hover {
            background: darken($color-primary, 5%);
            color: $color-white;
        }
    }

    // Disabled (prev on page 1, next on last page)
    &.pagination__link--disabled {
        color: $color-text-muted;
        opacity: 0.4;
        pointer-events: none;
        cursor: default;
    }

    // Ellipsis — not clickable
    &.pagination__link--ellipsis {
        pointer-events: none;
        color: $color-text-muted;
    }

    // Previous / Next arrows
    &.pagination__link--prev,
    &.pagination__link--next {
        font-size: 0;
        line-height: 0;

        &::before {
            font-size: $font-size-sm;
            line-height: 1;
        }
    }

    &.pagination__link--prev::before {
        content: '\2039'; // single left-pointing angle quotation mark
    }

    &.pagination__link--next::before {
        content: '\203A'; // single right-pointing angle quotation mark
    }
}

// Small size
.pagination--sm {
    .pagination__link {
        min-width: 1.75rem;
        height: 1.75rem;
        font-size: $font-size-xs;
        padding: 0 $space-xs;
    }
}
```

### Blade Component

**`resources/views/components/pagination.blade.php`:**

```blade
@props([
    'paginator' => null,
    'size' => 'md',
])

@php
$sizeClass = $size === 'sm' ? 'pagination--sm' : '';
@endphp

@if($paginator && $paginator->hasPages())
<nav aria-label="Pagination">
    <ul class="pagination {{ $sizeClass }}" {{ $attributes }}>
        {{-- Previous --}}
        <li class="pagination__item">
            <a href="{{ $paginator->previousPageUrl() ?? '#' }}"
                class="pagination__link pagination__link--prev"
                @if($paginator->onFirstPage())
                aria-disabled="true" tabindex="-1"
                @endif
                :class="{ 'pagination__link--disabled': {{ $paginator->onFirstPage() ? 'true' : 'false' }} }"
                aria-label="Previous page">
                Previous
            </a>
        </li>

        {{-- Pages --}}
        @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if($page === 1 || $page === $paginator->lastPage() || abs($page - $paginator->currentPage()) <= 2)
                @if(isset($prevPage) && $page - $prevPage > 1)
                    {{-- Ellipsis --}}
                    <li class="pagination__item">
                        <span class="pagination__link pagination__link--ellipsis" aria-hidden="true">...</span>
                    </li>
                @endif

                <li class="pagination__item">
                    <a href="{{ $url }}"
                        class="pagination__link"
                        :class="{ 'pagination__link--active': {{ $page === $paginator->currentPage() ? 'true' : 'false' } }"
                        @if($page === $paginator->currentPage())
                        aria-current="page"
                        @endif
                        aria-label="Page {{ $page }}">
                        {{ $page }}
                    </a>
                </li>

                @php $prevPage = $page; @endphp
            @endif
        @endforeach

        {{-- Next --}}
        <li class="pagination__item">
            <a href="{{ $paginator->nextPageUrl() ?? '#' }}"
                class="pagination__link pagination__link--next"
                @if(!$paginator->hasMorePages())
                aria-disabled="true" tabindex="-1"
                @endif
                :class="{ 'pagination__link--disabled': {{ !$paginator->hasMorePages() ? 'true' : 'false' } }"
                aria-label="Next page">
                Next
            </a>
        </li>
    </ul>
</nav>
@endif
```

### Usage

```blade
{{-- Controller passes paginated results --}}
<x-pagination :paginator="$books" />

{{-- Small variant --}}
<x-pagination :paginator="$authors" size="sm" />

{{-- With total count label --}}
<div class="flex flex--center-between">
    <p class="body">Showing {{ $books->firstItem() }}–{{ $books->lastItem() }} of {{ $books->total() }}</p>
    <x-pagination :paginator="$books" />
</div>
```

## Composition Rules

| Can contain (pagination) | Can contain (each item) |
|---|---|
| `<li>` items only (prev, page numbers, next) | `<a>` link with `pagination__link` class |

| Cannot contain | Cannot be inside |
|---|---|
| Buttons, forms, or interactive elements | Another pagination component |
| Dropdowns | Form |
| | Card body (use standalone above/below table) |

**Alpine:** None required. Pagination is pure Blade rendering — no client-side state.

**Ellipsis logic:** Shows page 1, last page, current ± 2 pages, with `...` between gaps. Standard pagination clipping pattern.

## Accessibility

- Wrapped in `<nav>` with `aria-label="Pagination"`
- Page links have `aria-label="Page N"`
- Previous/Next have `aria-label="Previous page"` / `aria-label="Next page"`
- Active page has `aria-current="page"`
- Disabled prev/next have `aria-disabled="true"` and `tabindex="-1"`
- Ellipsis uses `<span>` (not an `<a>`) with `aria-hidden="true"` — not focusable

## Acceptance Criteria

1. `_pagination.scss` compiles without errors
2. Component renders nothing when paginator has only one page
3. Previous link is disabled on page 1
4. Next link is disabled on the last page
5. Current page has `aria-current="page"` and distinct visual style
6. Ellipsis appears when page range exceeds current ± 2 + boundaries
7. `size="sm"` produces smaller page links
8. All page links are `<a>` elements with correct `href`
9. Wrapping `<nav>` has `aria-label="Pagination"`
10. Previous/Next render as arrow characters (‹ ›) with screen-reader labels
