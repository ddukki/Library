# List

## Purpose

Styled unordered and ordered lists for displaying sets of items. Used for author lists, location type lists, book lists, and metadata groupings.

## Variants

| Variant | Prop | Description |
|---------|------|-------------|
| Bare | `style="bare"` | No bullets/markers, no borders (default) |
| Divided | `style="divided"` | Items separated by bottom borders |
| Inline | `style="inline"` | Horizontal list with dot separators |

### Types

| Type | Prop | Description |
|------|------|-------------|
| Unordered | `type="unordered"` | `<ul>` (default) |
| Ordered | `type="ordered"` | `<ol>` with numbered markers |

### Modifiers

- `draggable` — grab cursor on items, visual drag states
- `handle` (on list-item) — grip icon before content

## Do

```blade
<x-list>
    <x-list-item>Item 1</x-list-item>
    <x-list-item>Item 2</x-list-item>
</x-list>

<x-list style="divided">
    <x-list-item>Author: Tolkien</x-list-item>
</x-list>

<x-list style="inline">
    <x-list-item>Fantasy</x-list-item>
    <x-list-item>Fiction</x-list-item>
</x-list>

<x-list type="ordered">
    @foreach ($books as $book)
        <x-list-item>{{ $book->title }}</x-list-item>
    @endforeach
</x-list>
```

## Don't

```blade
{{-- Don't put block elements directly in list --}}
<x-list>
    <div>Not a list item</div>
</x-list>
```

## Composition Rules

| Can contain (list) | Can contain (list item) |
|--------------------|-------------------------|
| List items only | Text |
| — | Inline elements (badge, link) |
| — | Form inputs (checkbox list) |
| — | Button (action item) |

| Cannot contain |
|----------------|
| Divs, headings, or block elements directly |
| Buttons, forms, or interactive content directly |

## Accessibility

- Draggable lists should have `role="listbox"` and items `role="option"` for keyboard reordering
- Drag handle has `aria-hidden="true"`

## Responsive Behavior

- Inline list wraps via `flex-wrap: wrap`
- Divided and bare lists remain stacked
