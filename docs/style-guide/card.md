# Card

## Purpose

Flexible content container for book cards, author cards, shelf panels, and dashboard widgets. Supports header, body, footer sections, image in four positions, clickable variant, and nested cards.

## Variants

| Variant | Prop | Description |
|---------|------|-------------|
| Default | — | Standard card with border, radius, shadow |
| Clickable | `clickable` | Entire card is a link with hover lift effect |
| Compact | `compact` | Reduced padding for dense layouts |

### Image Positions

| Position | Prop | Description |
|----------|------|-------------|
| Top | `image-position="top"` | Full-width image above header (default) |
| Bottom | `image-position="bottom"` | Full-width image below footer |
| Left | `image-position="left"` | Side-by-side, image on left at sm+ |
| Right | `image-position="right"` | Side-by-side, image on right at sm+ |

## Do

```blade
<x-card>Basic content.</x-card>

<x-card>
    <x-slot:header>Book Details</x-slot:header>
    <p>Content here.</p>
    <x-slot:footer>
        <x-button variant="secondary">Edit</x-button>
    </x-slot:footer>
</x-card>

<x-card clickable href="{{ route('books.show', $book) }}">
    <h3>{{ $book->title }}</h3>
</x-card>

<x-card compact>Compact content.</x-card>
```

## Don't

```blade
{{-- Don't put card footer/header directly without slots --}}
<x-card>
    <div class="card__header">Wrong</div>
</x-card>

{{-- Don't nest clickable cards inside interactive elements --}}
<button>
    <x-card clickable>Nope</x-card>
</button>
```

## Composition Rules

| Can contain (body) | Can contain (header) | Can contain (footer) |
|--------------------|----------------------|----------------------|
| Text / headings | Heading / title text | Buttons |
| Lists | Badge | Links |
| Form inputs | Search input | Pagination |
| Table | Button (action toolbar) | Text |
| Another card (nested) | — | — |
| Alert | — | — |

## Accessibility

- Clickable cards wrap content in `<a>` with `aria-label`
- Clickable cards must not nest interactive elements (buttons, links)
- Card headers should use appropriate heading levels
- Card images must have `image-alt` prop set

## Responsive Behavior

- Left/right image cards collapse to stacked layout on mobile (below `sm` breakpoint)
- Top/bottom images remain full-width at all sizes
