# Spec 023: Card Component

**Status:** Approved

**References:** ADR-0020, Spec 018

## Objective

Build the Card component — a flexible content container used throughout the app for book cards, author cards, shelf panels, and dashboard widgets. Must support header, body, footer sections, image in four positions, clickable variant, and nested cards.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_card.scss`
- Blade component: `resources/views/components/card.blade.php`
- Sections: `image`, `header`, `body` (default slot), `footer`
- Four image positions: `top`, `bottom`, `left`, `right`
- Clickable variant (entire card is a link with hover effect)
- Compact variant (reduced padding for dense layouts)
- Content sections (`card__image`, `card__header`, `card__body`, `card__footer`)
- Padding token alignment

### Out of Scope

## Interfaces

### SCSS: `resources/sass/components/_card.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/borders' as *;
@use '../tokens/shadows' as *;
@use '../tokens/transitions' as *;
@use '../primitives/responsive' as *;

.card {
    display: flex;
    flex-direction: column;
    background: $color-white;
    border: $border-width-thin solid $color-border;
    border-radius: $radius-md;
    box-shadow: $shadow-sm;
    overflow: hidden;

    &--clickable {
        cursor: pointer;
        transition: box-shadow $transition-base, transform $transition-base;

        &:hover {
            box-shadow: $shadow-md;
            transform: translateY(-2px);
        }

        a {
            text-decoration: none;
            color: inherit;
            display: block;
        }
    }

    &--compact {
        .card__header,
        .card__body,
        .card__footer {
            padding: $space-sm $space-md;
        }
    }

    // Image — top and bottom are full-width (natural flex column order)
    &__image {
        overflow: hidden;
        line-height: 0;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
    }

    &__image--top {
        order: -2;

        & + .card__header {
            border-top: none;
        }
    }

    &__image--bottom {
        order: 2;

        & + .card__footer {
            border-bottom: none;
        }
    }

    // Image — left and right (horizontal layout)
    &--horizontal {
        flex-direction: column;

        @include respond-to('sm') {
            flex-direction: row;

            .card__image--left,
            .card__image--right {
                flex: 0 0 280px;
                max-width: 35%;

                img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
            }

            .card__image--left {
                order: -1;
            }

            .card__image--right {
                order: 2;
            }

            .card__content {
                flex: 1;
                display: flex;
                flex-direction: column;
            }
        }
    }

    &__header {
        padding: $space-md $space-lg;
        border-bottom: $border-width-thin solid $color-border;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: $space-sm;
    }

    &__content {
        display: contents; // No layout wrapper by default; used by horizontal variant
    }

    &__body {
        padding: $space-lg;

        > :last-child {
            margin-bottom: 0;
        }
    }

    &__footer {
        padding: $space-md $space-lg;
        border-top: $border-width-thin solid $color-border;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: $space-sm;
    }
}
```

### Blade: `resources/views/components/card.blade.php`

```blade
@props([
    'clickable' => false,
    'compact' => false,
    'href' => null,
    'image' => null,
    'imageAlt' => '',
    'imagePosition' => 'top',  // top | bottom | left | right
])

@php
$classes = 'card';
if ($clickable) $classes .= ' card--clickable';
if ($compact) $classes .= ' card--compact';
$isHorizontal = in_array($imagePosition, ['left', 'right']);
if ($isHorizontal && $image) $classes .= ' card--horizontal';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if ($href)
        <a href="{{ $href }}" aria-label="{{ $ariaLabel ?? '' }}">
    @endif

    {{-- Image (top/bottom render before/after content; left/right inside horizontal pair) --}}
    @if ($image && in_array($imagePosition, ['top', 'bottom']))
        <div class="card__image card__image--{{ $imagePosition }}">
            <img src="{{ $image }}" alt="{{ $imageAlt }}" loading="lazy">
        </div>
    @endif

    {{-- Content wrapper (needed for horizontal layout — groups header+body+footer) --}}
    <div class="@if($isHorizontal && $image) card__content @endif">
        @if (isset($header))
            <div class="card__header">
                {{ $header }}
            </div>
        @endif

        @if (isset($body) || (string)$slot !== '')
            <div class="card__body">
                {{ $body ?? $slot }}
            </div>
        @endif

        @if (isset($footer))
            <div class="card__footer">
                {{ $footer }}
            </div>
        @endif
    </div>

    {{-- Image for left/right (renders inside horizontal pair) --}}
    @if ($image && $isHorizontal)
        <div class="card__image card__image--{{ $imagePosition }}">
            <img src="{{ $image }}" alt="{{ $imageAlt }}" loading="lazy">
        </div>
    @endif

    @if ($href)
        </a>
    @endif
</div>
```

### Usage

```blade
{{-- Basic card --}}
<x-card>
    <p>This is card content.</p>
</x-card>

{{-- Card with header and footer --}}
<x-card>
    <x-slot:header>Book Details</x-slot:header>
    <p>Content here.</p>
    <x-slot:footer>
        <x-button variant="secondary">Edit</x-button>
    </x-slot:footer>
</x-card>

{{-- Clickable card (linked) --}}
<x-card clickable href="{{ route('books.show', $book) }}">
    <h3>{{ $book->title }}</h3>
    <p>{{ $book->author->name }}</p>
</x-card>

{{-- Compact card for dense lists --}}
<x-card compact>
    <p>Compact content.</p>
</x-card>

{{-- Card with image on top --}}
<x-card image="https://..." image-alt="Book cover">
    <h3>The Hobbit</h3>
</x-card>

{{-- Card with image on bottom --}}
<x-card image="https://..." image-alt="Author photo" image-position="bottom">
    <p>J.R.R. Tolkien was an English writer...</p>
</x-card>

{{-- Card with image on left --}}
<x-card image="https://..." image-alt="Profile photo" image-position="left">
    <h3>J.R.R. Tolkien</h3>
    <p>Born 1892</p>
</x-card>

{{-- Card with image on right (stacks on mobile, side-by-side on sm+) --}}
<x-card image="https://..." image-alt="Illustration" image-position="right">
    <p>Content beside the image.</p>
</x-card>
```

## Composition Rules

| Can contain (body) | Can contain (header) | Can contain (footer) |
|---|---|---|
| Text / headings | Heading / title text | Buttons |
| Lists (ordered, unordered) | Badge | Links |
| Form inputs | Search input | Pagination |
| Table | Button (action toolbar) | Text |
| Another card (nested) | — | — |
| Alert | — | — |
| Badge | — | — |

| Cannot contain | Cannot be inside |
|---|---|
| Another card's footer/header directly (use slots) | Another card's body slot |
| Page-level layout containers | Button |
| — | Dropdown menu |
| — | Nav (nav items are not cards) |

**Image composition rules:**
- Top/bottom images are full-width; left/right images are 280px / 35% max-width, stacked on mobile
- Top image renders above the header; bottom image renders below the footer
- Left/right images render beside the content wrapper (header + body + footer as a group)
- Image aspect ratio is determined by the source image — use `ratio` primitive on the consumer side for fixed aspect ratios
- `loading="lazy"` is set by default on all card images

## Accessibility

- Clickable cards wrap content in an `<a>` tag with `aria-label` for context
- Clickable cards must not nest interactive elements (buttons, links) inside the card body
- Card headers should use appropriate heading levels (`<h2>`, `<h3>`) — not assumed by component
- Card images must have `image-alt` prop set to a meaningful description; decorative images should use `image-alt=""`
- Left/right image cards must remain functional at all viewport widths (horizontal layout collapses to stacked on mobile)

## Acceptance Criteria

1. `_card.scss` compiles without errors
2. Card renders with correct border, radius, shadow, and padding
3. Card header and footer show when their slots are provided
4. Card header and footer are hidden when their slots are absent
5. Clickable card shows hover lift effect
6. Compact variant reduces padding
7. Nested cards render correctly (card inside card body)
8. Card with `image` and `image-position="top"` renders a full-width image above the body
9. Card with `image-position="bottom"` renders a full-width image below the footer
10. Card with `image-position="left"` renders a horizontal layout with image on the left at `sm+` breakpoint, stacked on mobile
11. Card with `image-position="right"` renders a horizontal layout with image on the right at `sm+` breakpoint, stacked on mobile
12. Card without `image` renders no image element regardless of `image-position`
13. `npm run build` succeeds
