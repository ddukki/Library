@props([
    'handle' => false,
])

<li {{ $attributes->merge(['class' => 'list__item']) }}>
    @if($handle)
    <span class="list__handle" aria-hidden="true"></span>
    @endif
    {{ $slot }}
</li>
