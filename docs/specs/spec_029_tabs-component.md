# Spec 029: Tabs Component

**Status:** Approved

**References:** ADR-0020, Spec 018, Spec 025 (Nav — tabs are distinct: toggle content sections, not navigate routes)

## Objective

Build the Tabs component — tabbed interface for switching between related content sections without page navigation. Used for detail views (book info / editions / quotes) and settings forms.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_tabs.scss`
- Blade component: `resources/views/components/tabs.blade.php`
- Blade sub-components: `resources/views/components/tab.blade.php`, `components/tab-panel.blade.php`
- Horizontal layout (default)
- Two visual styles: `underline` (default) and `pills`
- Vertical orientation
- Alpine `x-data` tracking active tab by name
- Active tab indicator
- Disabled tab
- Tab panel toggles via `x-show` with fade transition (`x-transition`)
- Overflow scroll on tab list (horizontal scroll when tabs exceed container width)
- `role="tablist"`, `role="tab"`, `role="tabpanel"`, `aria-selected`, `aria-controls`, `aria-labelledby`

### Out of Scope

- Dynamic tabs (add/remove at runtime) — requires JS list management
- Lazy loading panels (load on first activation) — requires JS + network
- Closable tabs (browser-like tab pattern) — requires JS state management
- Tab overflow "more" dropdown — requires JS to detect overflow and render dropdown

## Interfaces

### SCSS: `resources/sass/components/_tabs.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/transitions' as *;

// Tab list
.tabs {
    display: flex;
    gap: 0;
    border-bottom: 1px solid $color-border;
    overflow-x: auto;
    flex-wrap: nowrap;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;



    &--pills {
        border-bottom: none;
        gap: $space-xs;
    }

    &--vertical {
        flex-direction: column;
        border-bottom: none;
        border-right: 1px solid $color-border;
    }
}

// Tab button
.tab {
    display: inline-flex;
    align-items: center;
    gap: $space-xs;
    padding: $space-sm $space-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $color-text-muted;
    cursor: pointer;
    white-space: nowrap;
    border: none;
    background: none;
    transition: color $transition-fast, border-color $transition-fast, background $transition-fast;

    // Underline
    .tabs:not(.tabs--pills) & {
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
    }
    .tabs:not(.tabs--pills) &:hover {
        color: $color-text;
        border-bottom-color: $color-border;
    }
    .tabs:not(.tabs--pills) &.tab--active {
        color: $color-primary;
        border-bottom-color: $color-primary;
    }

    // Pills
    .tabs--pills & {
        padding: $space-xs $space-md;
        border-radius: $radius-full;
        border: none;
    }
    .tabs--pills &:hover {
        background: $color-bg-alt;
        color: $color-text;
    }
    .tabs--pills &.tab--active {
        background: $color-primary;
        color: $color-white;
    }

    // Vertical
    .tabs--vertical & {
        border-bottom: none;
        border-right: 2px solid transparent;
        margin-right: -1px;
    }
    .tabs--vertical &:hover {
        color: $color-text;
        border-right-color: $color-border;
    }
    .tabs--vertical &.tab--active {
        color: $color-primary;
        border-right-color: $color-primary;
    }

    // Disabled
    &.tab--disabled {
        opacity: 0.4;
        pointer-events: none;
        cursor: default;
    }

    &:focus-visible {
        outline: 2px solid $color-primary;
        outline-offset: -2px;
    }

    .tab__icon {
        width: 1em;
        height: 1em;
        flex-shrink: 0;
    }
}

// Tab panel
.tab-panel {
    padding-top: $space-md;
}

// Transition classes
.tab-panel--enter {
    transition: opacity $transition-normal, transform $transition-normal;
}

.tab-panel--enter-start {
    opacity: 0;
    transform: translateY(4px);
}

.tab-panel--enter-end {
    opacity: 1;
    transform: translateY(0);
}
```

### Blade Components

**`resources/views/components/tabs.blade.php`:**

```blade
@props([
    'variant' => 'underline',  // underline | pills
    'orientation' => 'horizontal',  // horizontal | vertical
    'active' => '',
])

@php
$variantClass = $variant === 'pills' ? 'tabs--pills' : '';
$orientationClass = $orientation === 'vertical' ? 'tabs--vertical' : '';
$wrapperClass = $orientation === 'vertical' ? 'd-flex' : '';
@endphp

<div x-data="{ activeTab: '{{ $active }}' }" class="{{ $wrapperClass }}">
    <div class="tabs {{ $variantClass }} {{ $orientationClass }}"
        role="tablist"
        {{ $attributes }}>
        {{ $tabs }}
    </div>

    <div class="tab-panels">
        {{ $slot }}
    </div>
</div>
```

**`resources/views/components/tab.blade.php`:**

```blade
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
```

**`resources/views/components/tab-panel.blade.php`:**

```blade
@props([
    'name' => '',
    'label' => '',
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
```

### Usage

```blade
<x-tabs active="details">
    <x-slot:tabs>
        <x-tab name="details" label="Details" />
        <x-tab name="editions" label="Editions" icon="{!! svg('book') !!}" />
        <x-tab name="quotes" label="Quotes" :disabled="!$book->quotes->count()" />
    </x-slot:tabs>

    <x-tab-panel name="details">
        <dl>...</dl>
    </x-tab-panel>

    <x-tab-panel name="editions">
        @foreach($book->editions as $edition)
            ...
        @endforeach
    </x-tab-panel>

    <x-tab-panel name="quotes">
        @include('library.quotes._list', ['quotes' => $book->quotes])
    </x-tab-panel>
</x-tabs>

{{-- Pills variant --}}
<x-tabs variant="pills" active="open">
    <x-slot:tabs>
        <x-tab name="open" label="Open (3)" />
        <x-tab name="completed" label="Completed (12)" />
    </x-slot:tabs>
    ...
</x-tabs>

{{-- Vertical --}}
<x-tabs orientation="vertical" active="profile" class="d-flex">
    <x-slot:tabs>
        <x-tab name="profile" label="Profile" />
        <x-tab name="security" label="Security" />
    </x-slot:tabs>
    ...
</x-tabs>
```

## Composition Rules

| Can contain (tabs) | Can contain (tab) |
|---|---|
| `$tabs` slot (tab components only) | Text label |
| `$slot` (tab-panel components only) | Icon slot |

| Cannot contain in tabs | Cannot contain in tab-panel |
|---|---|
| Non-tab elements in `$tabs` slot | Another tabs component (no nesting) |

## Accessibility

- `role="tablist"` on the tabs container
- `role="tab"` + `aria-selected` + `aria-controls` on each tab button
- `role="tabpanel"` + `aria-labelledby` on each panel
- Disabled tabs get `disabled` attribute + reduced opacity
- Active tab determined by `activeTab` Alpine state
- Panels toggled via `x-show` (hidden content remains in DOM, just hidden)

## Acceptance Criteria

1. `_tabs.scss` compiles without errors
2. Tab list renders with `role="tablist"`
3. Each tab renders as `<button>` with `role="tab"` and `aria-selected`
4. Default `underline` variant shows bottom-border indicator on active tab
5. `pills` variant shows filled background on active tab
6. Vertical orientation renders tabs in a column with right-border indicator
7. `disabled` tab has reduced opacity and `pointer-events: none`
8. Clicking a tab sets `activeTab` and shows corresponding panel
9. Panel content hides/shows via `x-show` based on `activeTab`
10. Each panel has `role="tabpanel"` and correct `aria-labelledby`
11. `aria-controls` on tab matches panel `id`
12. `icon` prop renders inside the tab button
13. Active tab retains correct indicator after switching
14. Tab list scrolls horizontally when tabs exceed container width
15. Panel content fades in with translateY on tab switch
