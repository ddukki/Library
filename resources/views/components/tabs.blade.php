@props([
    'variant' => 'underline',
    'orientation' => 'horizontal',
    'active' => '',
])

@php
$variantClass = $variant === 'pills' ? 'tabs--pills' : '';
$orientationClass = $orientation === 'vertical' ? 'tabs--vertical' : '';
$wrapperClass = $orientation === 'vertical' ? 'd-flex' : '';
@endphp

<div x-data="{ activeTab: '{{ $active }}' }" class="{{ $wrapperClass }}">
    <div class="tabs {{ $variantClass }} {{ $orientationClass }}"
        role="tablist"
        {{ $attributes }}>
        {{ $tabs }}
    </div>

    <div class="tab-panels">
        {{ $slot }}
    </div>
</div>
