@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
])

@php
$activeClass = $active ? 'nav__link--active' : '';
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'nav__link ' . $activeClass]) }}
        @if($active) aria-current="page" @endif>
        @if($icon)<span class="nav__link-icon">{!! $icon !!}</span>@endif
        {{ $slot }}
    </a>
</li>
