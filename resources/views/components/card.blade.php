@props([
    'image' => null,
    'imagePosition' => 'top',  // top | bottom | left | right
    'clickable' => false,
    'compact' => false,
    'href' => null,
])

@php
$imageClass = $image ? 'card--has-image-' . $imagePosition : '';
$clickableClass = $clickable ? 'card--clickable' : '';
$compactClass = $compact ? 'card--compact' : '';

$tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => 'card ' . $imageClass . ' ' . $clickableClass . ' ' . $compactClass]) }}
    @if($href) href="{{ $href }}" @endif
    @if($clickable && $tag === 'div') role="button" tabindex="0" @endif>

    @if($image && in_array($imagePosition, ['top', 'left']))
    <div class="card__img">{!! $image !!}</div>
    @endif

    @if(isset($header))
    <div class="card__header">
        {{ $header }}
    </div>
    @endif

    @if(trim($slot))
    <div class="card__body">
        {{ $slot }}
    </div>
    @endif

    @if(isset($footer))
    <div class="card__footer">
        {{ $footer }}
    </div>
    @endif

    @if($image && in_array($imagePosition, ['bottom', 'right']))
    <div class="card__img">{!! $image !!}</div>
    @endif
</{{ $tag }}>
