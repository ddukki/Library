# Alert

## Purpose

Dismissible, varianted message bar for success, error, warning, and info states. Used for flash messages, validation summaries, and status notifications.

## Variants

| Variant | Modifier | Description |
|---------|----------|-------------|
| Success | `variant="success"` | Green — success confirmation |
| Danger | `variant="danger"` | Red — error messages |
| Warning | `variant="warning"` | Gold — warnings |
| Info | `variant="info"` | Blue — informational |

### Toast Mode

Add `toast` prop for fixed-position floating alert. Use `position` prop to set corner: `top-right` (default), `top-left`, `bottom-right`, `bottom-left`.

## Do

```blade
<x-alert variant="success">
    Book saved successfully.
</x-alert>

<x-alert variant="danger">
    Please fix the errors below.
</x-alert>

<x-alert variant="warning" dismissible>
    This shelf contains books. Delete them first.
</x-alert>

<x-alert toast variant="success" dismissible>
    Book saved successfully.
</x-alert>
```

## Don't

```blade
{{-- Don't nest alerts --}}
<x-alert>
    <x-alert variant="danger">Nope</x-alert>
</x-alert>

{{-- Don't put form inputs inside an alert --}}
<x-alert>
    <input type="text">
</x-alert>
```

## Composition Rules

| Can contain | Can be inside |
|-------------|---------------|
| Text content | Any container (card body, modal body, layout wrapper) |
| Inline links | Card body |
| Icons (via `alert__icon` slot) | — |

| Cannot contain | Cannot be inside |
|----------------|------------------|
| Buttons (use card footer) | Another alert |
| Form inputs | Dropdown menu |
| Nested alerts | Button |
| Cards or other components | Nav |

## Accessibility

- `role="alert"` on the container — screen readers announce toast content immediately
- Dismiss button has `aria-label="Dismiss alert"`
- Color is not the only indicator — icon provides semantic cue
- Toasts must not trap focus (they're not dialogs)

## Responsive Behavior

Static — no responsive changes. Toast width is `360px` with `max-width: calc(100vw - 16px * 2)`.

## Future Considerations

- Slide-in animation via Alpine `x-transition` (consumer-side)
- Stacked toast container component
