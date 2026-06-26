@props([
    'name' => '',
    'label' => '',
    'disabled' => false,
    'icon' => null,
])

<button type="button"
    role="tab"
    :aria-selected="activeTab === '{{ $name }}'"
    :tabindex="activeTab === '{{ $name }}' ? '0' : '-1'"
    :aria-controls="'panel-' + '{{ $name }}'"
    :id="'tab-' + '{{ $name }}'"
    x-on:click="activeTab = '{{ $name }}'"
    {{ $attributes->merge(['class' => 'tab']) }}
    :class="{ 'tab--active': activeTab === '{{ $name }}', 'tab--disabled': {{ $disabled ? 'true' : 'false' }} }"
    @if($disabled) disabled @endif>
    @if($icon)
    <span class="tab__icon">{!! $icon !!}</span>
    @endif
    {{ $label ?? $slot }}
</button>
