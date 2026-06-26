# Tabs

## Purpose

Tabbed interface for switching between related content sections without page navigation. Used for detail views (book info / editions / quotes) and settings forms.

## Variants

| Variant | Prop | Description |
|---------|------|-------------|
| Underline | `variant="underline"` | Bottom-border indicator (default) |
| Pills | `variant="pills"` | Filled background indicator |

### Orientation

| Orientation | Prop | Description |
|-------------|------|-------------|
| Horizontal | `orientation="horizontal"` | Tabs in a row (default) |
| Vertical | `orientation="vertical"` | Tabs in a column |

### Sub-Components

- `<x-tab>` — individual tab button with `name`, `label`, `disabled`, `icon`
- `<x-tab-panel>` — content panel associated with a tab by `name`

## Do

```blade
<x-tabs active="details">
    <x-slot:tabs>
        <x-tab name="details" label="Details" />
        <x-tab name="editions" label="Editions" icon="{!! svg('book') !!}" />
        <x-tab name="quotes" label="Quotes" :disabled="!$book->quotes->count()" />
    </x-slot:tabs>
    <x-tab-panel name="details">...</x-tab-panel>
    <x-tab-panel name="editions">...</x-tab-panel>
    <x-tab-panel name="quotes">...</x-tab-panel>
</x-tabs>
```

## Don't

```blade
{{-- Don't put non-tab elements in tabs slot --}}
<x-tabs>
    <x-slot:tabs>
        <div>Not a tab</div>
    </x-slot:tabs>
</x-tabs>
```

## Composition Rules

| Can contain (tabs) | Can contain (tab) |
|--------------------|-------------------|
| `$tabs` slot (tab components only) | Text label |
| `$slot` (tab-panel components only) | Icon slot |

| Cannot contain in tabs | Cannot contain in tab-panel |
|------------------------|----------------------------|
| Non-tab elements in `$tabs` slot | Another tabs component (no nesting) |

## Accessibility

- `role="tablist"` on the tabs container
- `role="tab"` + `aria-selected` + `aria-controls` on each tab button
- `role="tabpanel"` + `aria-labelledby` on each panel
- Disabled tabs get `disabled` attribute + reduced opacity
- Panels toggled via `x-show` (hidden content remains in DOM)

## Responsive Behavior

- Tab list scrolls horizontally when tabs exceed container width (`overflow-x: auto`)
- Vertical orientation remains stacked at all viewport sizes
