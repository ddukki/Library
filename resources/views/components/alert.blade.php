@props([
    'variant' => 'info',
    'dismissible' => false,
    'toast' => false,
    'position' => 'br',  // br | bl | tr | tl
])

@php
$variantClass = 'alert--' . $variant;
$toastClass = $toast ? 'alert--toast alert--toast-' . $position : '';
@endphp

<div role="alert"
    {{ $attributes->merge(['class' => 'alert ' . $variantClass . ' ' . $toastClass]) }}>
    <div class="alert__content">
        {{ $slot }}
    </div>

    @if($dismissible)
    <button type="button" class="alert__dismiss"
        x-data
        @click="$el.closest('.alert').remove()"
        aria-label="Dismiss">
        &times;
    </button>
    @endif
</div>
