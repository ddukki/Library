@props([
    'variant' => 'light',  // light | dark
    'orientation' => 'horizontal',  // horizontal | vertical
    'sticky' => false,
    'brand' => null,
    'brandUrl' => '/',
])

@php
$variantClass = $variant === 'dark' ? 'nav--dark' : '';
$orientationClass = $orientation === 'vertical' ? 'nav--vertical' : '';
$stickyClass = $sticky ? 'nav--sticky' : '';
@endphp

<nav {{ $attributes->merge(['class' => 'nav ' . $variantClass . ' ' . $orientationClass . ' ' . $stickyClass]) }}
    x-data="{ navOpen: false }">

    @if($brand)
    <a href="{{ $brandUrl }}" class="nav__brand">
        {{ $brand }}
    </a>
    @endif

    <button class="nav__toggle" @click="navOpen = !navOpen" aria-label="Toggle navigation">
        &#9776;
    </button>

    <ul class="nav__links" :class="{ 'nav__links--open': navOpen }" @click.outside="navOpen = false">
        {{ $links }}
    </ul>

    @if(isset($end))
    <div class="nav__end">
        {{ $end }}
    </div>
    @endif
</nav>
