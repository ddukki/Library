@props([
    'variant' => 'bare',  // bare | ordered | divided | inline
    'draggable' => false,
    'tag' => null,
])

@php
$variantClass = match($variant) {
    'ordered' => 'list--ordered',
    'divided' => 'list--divided',
    'inline' => 'list--inline',
    default => '',
};

$draggableClass = $draggable ? 'list--draggable' : '';

$listTag = $tag ?? ($variant === 'ordered' ? 'ol' : 'ul');
@endphp

<{{ $listTag }} {{ $attributes->merge(['class' => 'list ' . $variantClass . ' ' . $draggableClass]) }}>
    {{ $slot }}
</{{ $listTag }}>
