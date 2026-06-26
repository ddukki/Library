@props([
    'avatars' => [],
    'size' => 'md',
    'max' => 3,
    'direction' => 'right',
])

@php
$sizeClass = 'avatar--' . $size;
$directionClass = $direction === 'left' ? 'avatar-group--left' : '';
$visible = array_slice($avatars, 0, $max);
$overflow = count($avatars) - $max;
@endphp

<div {{ $attributes->merge(['class' => 'avatar-group ' . $directionClass]) }}
    role="group"
    aria-label="Users">

    @if($overflow > 0)
    <span class="avatar-group__count avatar {{ $sizeClass }}"
        aria-label="{{ $overflow }} more" title="{{ $overflow }} more">
        +{{ $overflow }}
    </span>
    @endif

    @foreach(array_reverse($visible) as $avatar)
        <x-avatar :src="$avatar['src'] ?? null"
            :alt="$avatar['alt'] ?? ($avatar['name'] ?? '')"
            :initials="$avatar['initials'] ?? ''"
            :size="$size" />
    @endforeach
</div>
