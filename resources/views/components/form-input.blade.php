@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
])

@php
$isCheckRadio = in_array($type, ['checkbox', 'radio']);
$hasError = $error ?? ($errors ? $errors->first($name) : null);

$inputClasses = '';
if ($isCheckRadio) {
    $fieldClass = 'form-input__' . $type;
    $wrapperClass = 'form-input form-input--' . $type;
} else {
    $fieldClass = 'form-input__field';
    $wrapperClass = 'form-input';
}

$wrapperClass .= $required ? ' form-input--required' : '';
$wrapperClass .= $hasError ? ' form-input--invalid' : '';

$id = $attributes->get('id', $name);
@endphp

<div class="{{ $wrapperClass }}">
    @if($label && !$isCheckRadio)
        <label for="{{ $id }}" class="form-input__label">{{ $label }}</label>
    @endif

    @if($isCheckRadio)
        <label for="{{ $id }}" class="form-input__label">
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
                value="{{ $value }}" {{ $attributes->merge(['class' => $fieldClass]) }}
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
            {{ $label }}
        </label>
    @elseif($type === 'select')
        <select name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' => $fieldClass]) }}
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
            {!! $slot !!}
        </select>
    @elseif($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $id }}" placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => $fieldClass]) }}
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>{{ $value }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
            placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => $fieldClass]) }}
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
    @endif

    @if($help)
        <p class="form-input__help" id="{{ $id }}-help">{{ $help }}</p>
    @endif

    @if($hasError)
        <p class="form-input__error" id="{{ $id }}-error">{{ $hasError }}</p>
    @endif
</div>
