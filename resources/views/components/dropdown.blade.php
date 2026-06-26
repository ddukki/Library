@props([
    'placement' => 'left',
])

@php
$placementClass = match($placement) {
    'right' => 'dropdown__menu--right',
    'center' => 'dropdown__menu--center',
    default => '',
};
@endphp

<div x-data="{ open: false }"
    {{ $attributes->merge(['class' => 'dropdown']) }}
    :class="{ 'dropdown--open': open }">

    <div class="dropdown__toggle" @click="open = !open" @keydown.escape="open = false"
        role="button" tabindex="0" :aria-expanded="open" aria-haspopup="true">
        {{ $trigger }}
    </div>

    <div class="dropdown__menu {{ $placementClass }}" x-show="open"
        @click.outside="open = false" x-cloak>
        {{ $slot }}
    </div>
</div>
