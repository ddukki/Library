@props([
    'variant' => 'primary',
    'size' => 'md',   // sm | md | lg
    'clickable' => false,
    'togglable' => false,
    'active' => false,
])

@php
$variantClass = 'badge--' . $variant;
$sizeClass = $size !== 'md' ? 'badge--' . $size : '';
$clickableClass = $clickable ? 'badge--clickable' : '';
$togglableClass = $togglable ? 'badge--togglable' : '';
$activeClass = ($togglable && $active) ? 'badge--active' : '';

$tag = ($clickable || $togglable) ? 'button' : 'span';

$classes = 'badge ' . $variantClass . ' ' . $sizeClass . ' ' . $clickableClass . ' ' . $togglableClass . ' ' . $activeClass;
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => trim($classes)]) }}
    @if($tag === 'button') type="button"
        @if($togglable) x-data="{ active: {{ $active ? 'true' : 'false' }} }"
            :class="{ 'badge--active': active }"
            :aria-pressed="active"
            @click="active = !active"
        @endif
    @endif>
    {{ $slot }}
</{{ $tag }}>
