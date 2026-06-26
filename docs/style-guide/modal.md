# Modal

## Purpose

Overlay dialog for confirmations, forms, and detailed content that requires user attention without navigating away from the current page.

## Variants

| Size | Prop | Max-width |
|------|------|-----------|
| Small | `size="sm"` | 24rem (384px) |
| Medium | `size="md"` | 32rem (512px, default) |
| Large | `size="lg"` | 48rem (768px) |
| Extra Large | `size="xl"` | 64rem (1024px) |

## Do

```blade
<x-modal name="confirm-delete" title="Delete Book" size="sm">
    Are you sure you want to delete <strong>{{ $book->title }}</strong>?
    <x-slot:footer>
        <x-button variant="secondary" @click="open = false">Cancel</x-button>
        <x-button variant="danger">Delete</x-button>
    </x-slot:footer>
</x-modal>

<x-modal name="edit-book" title="Edit Book" size="lg">
    <form method="POST" action="{{ route('books.update', $book) }}">
        @csrf @method('PUT')
        <x-form-input name="title" label="Title" :value="$book->title" required />
        <x-slot:footer>
            <x-button variant="secondary" @click="open = false">Cancel</x-button>
            <x-button type="submit">Save</x-button>
        </x-slot:footer>
    </form>
</x-modal>
```

## Don't

```blade
{{-- Don't nest modals --}}
<x-modal name="outer">
    <x-modal name="inner">Don't</x-modal>
</x-modal>

{{-- Don't put cards inside modals (redundant layering) --}}
<x-modal>
    <x-card>Unnecessary</x-card>
</x-modal>
```

## Composition Rules

| Can contain (body) | Can contain (footer) |
|--------------------|----------------------|
| Any content | Button components |
| Forms | Text, form actions |

| Cannot contain | Cannot be inside |
|----------------|------------------|
| Another modal | Another modal |
| Long unformatted text without scroll | Card (redundant) |

## Dependencies

Requires `@alpinejs/focus` plugin for `x-trap.noscroll`.

## Accessibility

- `role="dialog"` and `aria-modal="true"` on the panel
- `aria-labelledby` links to modal title `h2`
- Close button has `aria-label="Close modal"`
- Escape key closes via `@keydown.escape.window`
- Click-outside on backdrop closes via `@click.self`
- **Focus trap** via Alpine `x-trap.noscroll` — Tab cycles within modal, body scroll locked

## Responsive Behavior

Static — panel max-width adjusts with `size` prop. Modal is centered at all viewport sizes. Body is scrollable when content overflows.
