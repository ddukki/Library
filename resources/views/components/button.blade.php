@props([
    'variant' => 'primary',  // primary | secondary | danger | link
    'size' => 'md',          // sm | md | lg
    'href' => null,
    'block' => false,
    'active' => false,
    'disabled' => false,
    'type' => 'button',
])

@php
$variantClass = 'btn--' . $variant;
$sizeClass = $size !== 'md' ? 'btn--' . $size : '';
$blockClass = $block ? 'btn--block' : '';

$classes = 'btn ' . $variantClass . ' ' . $sizeClass . ' ' . $blockClass;

$tag = $href ? 'a' : 'button';
@endphp

@if($tag === 'a')
<a href="{{ $href }}"
    {{ $attributes->merge(['class' => trim($classes)]) }}
    @if($disabled) aria-disabled="true" tabindex="-1" class="{{ trim($classes) . ' btn--disabled' }}" @endif
    role="button">
    {{ $slot }}
</a>
@else
<button type="{{ $type }}"
    {{ $attributes->merge(['class' => trim($classes)]) }}
    @if($disabled) disabled @endif
    @if($active) aria-pressed="true" @endif>
    {{ $slot }}
</button>
@endif
