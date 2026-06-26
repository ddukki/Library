@props([
    'name' => 'modal',
    'size' => 'md',
    'title' => '',
    'show' => false,
])

@php
$sizeClass = match($size) {
    'sm' => 'modal--sm',
    'lg' => 'modal--lg',
    'xl' => 'modal--xl',
    default => '',
};
@endphp

<div x-data="{ open: {{ $show ? 'true' : 'false' }} }"
    x-cloak
    x-show="open"
    @keydown.escape.window="open = false"
    x-trap.noscroll="open"
    class="modal-backdrop"
    @click.self="open = false">

    <div class="modal {{ $sizeClass }}"
        role="dialog"
        aria-modal="true"
        :aria-labelledby="'modal-title-' + '{{ $name }}'"
        @click.stop>

        <div class="modal__header">
            <h2 class="modal__title"
                :id="'modal-title-' + '{{ $name }}'"
                x-text="'{{ $title }}'">
            </h2>
            <button type="button" class="modal__close"
                @click="open = false"
                aria-label="Close modal" title="Close">
                &times;
            </button>
        </div>

        <div class="modal__body">
            {{ $slot }}
        </div>

        @if(isset($footer))
        <div class="modal__footer">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>
