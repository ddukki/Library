# Badge

## Purpose

Small label for status indicators, counts, and tags. Used for location types, progress statuses, and metadata pills.

## Variants

| Variant | Modifier | Description |
|---------|----------|-------------|
| Default | `variant="default"` | Neutral gray |
| Success | `variant="success"` | Green |
| Warning | `variant="warning"` | Gold |
| Danger | `variant="danger"` | Red |

### Sizes

| Size | Modifier | Description |
|------|----------|-------------|
| Small | `size="sm"` | 2px vertical padding |
| Medium | `size="md"` | 4px vertical padding (default) |

### Interactive

- `clickable` — renders as `<button>` with hover state and focus ring
- `togglable` — renders as `<button>` with active state for selected/unselected
- `active` — used with `togglable` to mark selected state

## Do

```blade
<x-badge>Paperback</x-badge>
<x-badge variant="success">Available</x-badge>
<x-badge variant="danger">Overdue</x-badge>
<x-badge size="sm">3</x-badge>

<x-badge togglable x-on:click="active = !active" :active="$active">
    Science Fiction
</x-badge>
```

## Don't

```blade
{{-- Don't put block elements in badges --}}
<x-badge><div>Text</div></x-badge>

{{-- Don't nest badges --}}
<x-badge><x-badge>Nested</x-badge></x-badge>
```

## Composition Rules

| Can contain | Can be inside |
|-------------|---------------|
| Text (short, 1-3 words) | Card header/footer |
| Numbers / counts | Table cells |
| Inline icons | List items |

| Cannot contain | Cannot be inside |
|----------------|------------------|
| Buttons | Another badge |
| Links | Form inputs |
| Form elements | Alert body |
| Block elements | |

## Accessibility

- Non-interactive badges render as `<span>` — no implicit role
- Clickable/togglable badges render as `<button>` — interactive, focusable, keyboard-activatable
- Focus ring uses `:focus-visible` — only shows for keyboard users
- If a static badge conveys status, ensure parent context makes meaning clear (don't rely on color alone)

## Responsive Behavior

Static — no responsive changes.
