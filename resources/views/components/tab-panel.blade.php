@props([
    'name' => '',
])

<div role="tabpanel"
    :aria-labelledby="'tab-' + '{{ $name }}'"
    :id="'panel-' + '{{ $name }}'"
    x-show="activeTab === '{{ $name }}'"
    x-transition:enter="tab-panel--enter"
    x-transition:enter-start="tab-panel--enter-start"
    x-transition:enter-end="tab-panel--enter-end"
    {{ $attributes->merge(['class' => 'tab-panel']) }}>
    {{ $slot }}
</div>
