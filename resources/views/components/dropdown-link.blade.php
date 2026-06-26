@props([
    'href' => '#',
    'disabled' => false,
])

@php
$classes = 'dropdown__item';
if ($disabled) $classes .= ' dropdown__item--disabled';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled) aria-disabled="true" tabindex="-1" @endif>
    {{ $slot }}
</a>
