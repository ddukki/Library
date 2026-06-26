# Form Input

## Purpose

Styled, accessible wrapper for all form input types (text, email, password, number, select, textarea, checkbox, radio). Includes validation state styling, labels, and error messages.

## Types

| Component | Description |
|-----------|-------------|
| `<x-form-input>` | Text, email, password, number, search, url |
| `<x-form-select>` | Select dropdown |
| `<x-form-textarea>` | Textarea |
| `<x-form-checkbox>` | Single checkbox |
| `<x-form-radio>` | Single radio button |

## Variants

| Prop | Values | Description |
|------|--------|-------------|
| `size` | `md` (default), `sm` | Input size |
| `required` | boolean | Shows `*` indicator |
| `error` | string | Validation error message |
| `hint` | string | Helper text below input |
| `label` | string | Label text |

### Validation States

- Invalid: red border, red focus shadow, error message with `role="alert"`
- Valid: green border, green focus shadow

## Do

```blade
<x-form-input name="title" label="Book Title" required />

<x-form-input name="email" type="email" label="Email"
    :value="old('email')" error="Invalid email" />

<x-form-select name="location_type" label="Location Type"
    :options="$locationTypes" />

<x-form-textarea name="notes" label="Notes" />

<x-form-checkbox name="agree" label="I agree" />
```

## Don't

```blade
{{-- Don't nest form fields --}}
<x-form-input name="x">
    <x-form-input name="y">Nested</x-form-input>
</x-form-input>

{{-- Don't put buttons inside form fields --}}
<x-form-input name="search">
    <button>Go</button>
</x-form-input>
```

## Composition Rules

| Can contain | Can be inside |
|-------------|---------------|
| Label text | Card body |
| Hint text | Form element (wrapping `<form>`) |
| Error message | Modal body |

| Cannot contain | Cannot be inside |
|----------------|------------------|
| Buttons | Another form field |
| Cards or block components | Alert body |
| Another form field | Dropdown menu |

## Accessibility

- Every `<input>`/`<select>`/`<textarea>` has a matching `<label>` with `for` attribute
- Required fields indicate with `*`
- Invalid fields get `aria-invalid="true"` and `aria-describedby` pointing to the error message
- Error messages have `role="alert"`
- Hint text connected via `aria-describedby`

## Responsive Behavior

Static — no responsive changes. Use `size="sm"` for compact layouts.
