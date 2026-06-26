# Spec 022: Form Input Component

**Status:** Approved

**References:** ADR-0020, Spec 018

## Objective

Build the Form Input component — a styled, accessible wrapper for all form input types (text, email, password, number, select, textarea, checkbox, radio). Must include validation state styling, labels, and error messages.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_form.scss`
- Blade components: `resources/views/components/form-input.blade.php`, `form-select.blade.php`, `form-textarea.blade.php`, `form-checkbox.blade.php`, `form-radio.blade.php`
- Input types: text, email, password, number, search, url
- Select dropdowns
- Textareas
- Checkboxes and radios (single and group)
- Validation states: `valid`, `invalid` with border color and icon
- Sizes: `md` (default), `sm` (inline/small forms)
- Floating label support (label inside input)

### Out of Scope

- File uploads (separate component in the future)
- Date pickers / autocompletes (future components)

## Interfaces

### SCSS: `resources/sass/components/_form.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/transitions' as *;

// Shared input base
%input-base {
    width: 100%;
    font-family: $font-family-body;
    font-size: $font-size-base;
    line-height: $line-height-base;
    color: $color-text;
    background: $color-white;
    border: $border-width-thin solid $color-border;
    border-radius: $radius-sm;
    transition: border-color $transition-fast, box-shadow $transition-fast;

    &:focus {
        outline: none;
        border-color: $color-primary;
        box-shadow: 0 0 0 3px rgba($color-primary, 0.15);
    }

    &::placeholder {
        color: $color-text-muted;
    }
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: $space-xs;

    &--sm {
        .input,
        .select,
        .textarea {
            padding: $space-xs $space-sm;
            font-size: $font-size-sm;
        }
    }

    &--inline {
        flex-direction: row;
        align-items: center;
        gap: $space-sm;
    }
}

.form-label {
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $color-text;

    .form-field--invalid & {
        color: $color-danger;
    }
}

.input {
    @extend %input-base;
    padding: $space-sm $space-md;
    height: 40px;
}

.select {
    @extend %input-base;
    padding: $space-sm $space-md;
    height: 40px;
    appearance: none;
    background-image: url("data:image/svg+xml,...");
    background-repeat: no-repeat;
    background-position: right $space-sm center;
    background-size: 1em;
    padding-right: $space-xl;
}

.textarea {
    @extend %input-base;
    padding: $space-sm $space-md;
    resize: vertical;
    min-height: 80px;
}

// Validation states
.form-field--invalid {
    .input, .select, .textarea {
        border-color: $color-danger;
        &:focus {
            box-shadow: 0 0 0 3px rgba($color-danger, 0.15);
        }
    }
}

.form-field--valid {
    .input, .select, .textarea {
        border-color: $color-success;
        &:focus {
            box-shadow: 0 0 0 3px rgba($color-success, 0.15);
        }
    }
}

.form-error {
    font-size: $font-size-sm;
    color: $color-danger;
    margin-top: 2px;
}

.form-hint {
    font-size: $font-size-sm;
    color: $color-text-muted;
    margin-top: 2px;
}

// Checkbox & Radio
.checkbox,
.radio {
    display: flex;
    align-items: center;
    gap: $space-sm;
    cursor: pointer;
    font-size: $font-size-base;

    input[type="checkbox"],
    input[type="radio"] {
        width: 1em;
        height: 1em;
        accent-color: $color-primary;
        cursor: pointer;
    }

    &--invalid input {
        accent-color: $color-danger;
    }
}
```

### Blade Components

**`resources/views/components/form-input.blade.php`:**

```blade
@props([
    'name' => '',
    'type' => 'text',
    'label' => '',
    'value' => '',
    'error' => '',
    'hint' => '',
    'size' => 'md',
    'required' => false,
    'placeholder' => '',
])

@php
$hasError = $error || $errors->has($name);
$classes = 'form-field';
if ($hasError) $classes .= ' form-field--invalid';
if ($size === 'sm') $classes .= ' form-field--sm';
@endphp

<div {{ $attributes->except(['class'])->merge(['class' => $classes]) }}>
    @if ($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if ($required) <span aria-hidden="true" class="form-label--required">*</span> @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($hasError) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        class="input @if($hasError) input--error @endif"
        {{ $attributes->only(['autocomplete', 'min', 'max', 'step', 'pattern', 'disabled', 'readonly']) }}
    >

    @if ($hint && !$hasError)
        <p class="form-hint" id="{{ $name }}-hint">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <p class="form-error" id="{{ $name }}-error" role="alert">
            {{ $error ?: $errors->first($name) }}
        </p>
    @endif
</div>
```

**`resources/views/components/form-select.blade.php`** — same wrapper pattern, uses `<select class="select">` and accepts `:options` array.

**`resources/views/components/form-textarea.blade.php`** — same wrapper pattern, uses `<textarea class="textarea">`.

**`resources/views/components/form-checkbox.blade.php`:**

```blade
@props([
    'name' => '',
    'label' => '',
    'checked' => false,
    'value' => '1',
])

<div {{ $attributes->merge(['class' => 'checkbox']) }}>
    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" @if($checked) checked @endif>
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif
</div>
```

**`resources/views/components/form-radio.blade.php`** — same pattern, `type="radio"`.

### Usage

```blade
{{-- Text input with label --}}
<x-form-input name="title" label="Book Title" required />

{{-- With error --}}
<x-form-input name="email" type="email" label="Email" :value="old('email')" error="Invalid email" />

{{-- Select --}}
<x-form-select name="location_type" label="Location Type" :options="$locationTypes" />

{{-- Textarea --}}
<x-form-textarea name="notes" label="Notes" />

{{-- Checkbox --}}
<x-form-checkbox name="agree" label="I agree to the terms" />
```

## Composition Rules

| Can contain | Can be inside |
|---|---|
| Label text | Card body |
| Validation icons/indicators | Card footer (submit area) |
| Hint text | Form element (wrapping `form` tag) |
| Error message | List item |
| — | Modal body |
| — | Nav (search input) |

| Cannot contain | Cannot be inside |
|---|---|
| Buttons | Another form field |
| Cards or block components | Alert body |
| Another form field | Dropdown menu |
| — | Badge |

## Accessibility

- Every `<input>`/`<select>`/`<textarea>` has a matching `<label>` with `for` attribute
- Required fields indicate with `*` and `aria-required="true"`
- Invalid fields get `aria-invalid="true"` and `aria-describedby` pointing to the error message
- Error messages have `role="alert"`
- Hint text is connected via `aria-describedby`
- Checkbox/radio labels are clickable (wrapping `<label>` or `for` attribute)

## Acceptance Criteria

1. `_form.scss` compiles without errors
2. All five input types render with consistent styling
3. Validation states show correct border colors (red for invalid, green for valid)
4. Error messages display below the input with proper aria linkage
5. `form-field--sm` reduces padding and font size
6. Required fields show `*` indicator
7. Form fields without a label prop render no `<label>` element
8. Checkbox/radio inputs are properly associated with their labels
