# Button

## Purpose

Primary interactive element for forms, actions, and navigation.

## Variants

| Variant | Modifier | Description |
|---------|----------|-------------|
| Primary | `variant="primary"` | Main call-to-action |
| Secondary | `variant="secondary"` | Alternative action |
| Danger | `variant="danger"` | Destructive action |
| Link | `variant="link"` | Appears as link, behaves as button |

### Sizes

| Size | Modifier |
|------|----------|
| Small | `size="sm"` |
| Medium | `size="md"` (default) |
| Large | `size="lg"` |

### Modifiers

- `block` — full-width
- `disabled` — reduced opacity, no pointer events
- `active` — pressed/toggled state with inset shadow
- `loading` — CSS-only spinner overlay (add `aria-busy="true"`)
- `href` — renders `<a>` instead of `<button>`
- `icon` — renders icon span before slot

## Do

```blade
<x-button>Save</x-button>
<x-button variant="secondary">Cancel</x-button>
<x-button variant="danger" size="sm">Delete</x-button>
<x-button variant="link">Edit</x-button>
<x-button block>Submit</x-button>
<x-button href="{{ route('authors.index') }}">Back</x-button>
```

## Don't

```blade
{{-- Don't nest buttons --}}
<x-button><x-button>Nested</x-button></x-button>

{{-- Don't put forms inside buttons --}}
<x-button><form>...</form></x-button>
```

## Composition Rules

| Can contain | Can be inside |
|-------------|---------------|
| Text (short action label) | Card footer |
| Icon (via `icon` prop) | Form (as submit) |
| Badge (count, sm only) | Modal footer |
| — | Nav, table actions |

| Cannot contain | Cannot be inside |
|----------------|------------------|
| Another button | Another button |
| Form inputs | Badge (inside a badge) |
| Cards, modals | Heading text |
| Dropdown menus | |

## Accessibility

- `<button>` gets `disabled` attribute when disabled
- `<a>` gets `aria-disabled="true"` and `tabindex="-1"` when disabled
- Active `<button>` gets `aria-pressed="true"`
- Active `<a>` gets `aria-current="page"`
- Focus styles use `:focus-visible` — outline only for keyboard users

## Responsive Behavior

Static — no responsive changes. Use `block` modifier for full-width on mobile.
