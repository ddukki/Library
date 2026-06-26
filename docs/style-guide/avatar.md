# Avatar

## Purpose

Circular image or initial-based placeholder for user profiles, author photos, and identity representation.

## Variants

| Size | Prop | Dimensions | Font Size |
|------|------|------------|-----------|
| Small | `size="sm"` | 24px | 12px |
| Medium | `size="md"` | 32px (default) | 14px |
| Large | `size="lg"` | 48px | 18px |
| Extra Large | `size="xl"` | 64px | 20px |

### Status Indicator

| Status | Description |
|--------|-------------|
| `status="online"` | Green dot |
| `status="away"` | Yellow dot |
| `status="busy"` | Red dot |

### Avatar Group

`<x-avatar-group>` — overlapping stack with overflow count.

| Prop | Values | Description |
|------|--------|-------------|
| `avatars` | array | Array of `{src?, alt?, initials?}` objects |
| `max` | number | Max visible avatars (default: 3) |
| `direction` | `right` or `left` | Stack direction (default: right) |

## Do

```blade
<x-avatar src="{{ auth()->user()->avatar_url }}"
    alt="{{ auth()->user()->name }}" size="sm" />

<x-avatar initials="JD" size="lg" />

<x-avatar initials="JD" size="md" status="online" />

<x-avatar-group :avatars="[
    ['src' => '/users/alice.jpg', 'alt' => 'Alice'],
    ['src' => '/users/bob.jpg', 'alt' => 'Bob'],
    ['initials' => 'EV', 'alt' => 'Eve'],
]" :max="3" size="sm" />
```

## Don't

```blade
{{-- Don't put text inside avatars --}}
<x-avatar>Name</x-avatar>

{{-- Don't put badges inside avatars --}}
<x-avatar><x-badge>3</x-badge></x-avatar>
```

## Composition Rules

| Can contain (avatar) | Can contain (avatar-group) |
|----------------------|----------------------------|
| `<img>` (when src provided) | Avatar components (via data array) |
| `<span>` initials | Overflow count badge |
| Status dot `<span>` | — |

| Cannot contain |
|----------------|
| Text or labels |
| Icons |
| Buttons |
| Badge (position separately) |

## Accessibility

- `role="img"` on the avatar container
- `aria-label` set to `alt` or `initials` text
- Image avatar uses `<img alt="">` for native fallback
- Status indicator has `aria-label` describing the status
- Avatar group has `role="group"` and `aria-label="Users"`
- Overflow count has `aria-label="N more"`

## Responsive Behavior

Static — no responsive changes. Avatar group collapses into row with overflow count.
