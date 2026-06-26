@props([
    'disabled' => false,
])

@php
$classes = 'dropdown__item';
if ($disabled) $classes .= ' dropdown__item--disabled';
@endphp

<button type="button" {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled) disabled @endif>
    {{ $slot }}
</button>
