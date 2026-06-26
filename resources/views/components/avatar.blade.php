@props([
    'src' => null,
    'alt' => '',
    'initials' => '',
    'size' => 'md',
    'status' => null,
])

@php
$sizeClass = 'avatar--' . $size;
$statusClass = $status ? 'avatar__status--' . $status : '';
@endphp

<div {{ $attributes->merge(['class' => 'avatar ' . $sizeClass]) }}
    role="img"
    aria-label="{{ $alt ?: $initials }}">

    @if($src)
        <img src="{{ $src }}" alt="{{ $alt }}" class="avatar__img">
    @else
        <span class="avatar__initials">{{ $initials }}</span>
    @endif

    @if($status)
        <span class="avatar__status {{ $statusClass }}"
            aria-label="{{ $status === 'online' ? 'Online' : ($status === 'away' ? 'Away' : 'Busy') }}">
        </span>
    @endif
</div>
