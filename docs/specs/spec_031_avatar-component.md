# Spec 031: Avatar Component

**Status:** Approved

**References:** ADR-0020, Spec 018, Spec 025 (Nav — avatar in user menu trigger)

## Objective

Build the Avatar component — a circular image or initial-based placeholder for user profiles, author photos, and any identity representation.

## Scope

### In Scope

- SCSS partial: `resources/sass/components/_avatar.scss`
- Blade component: `resources/views/components/avatar.blade.php`
- Image avatar (`<img>` with fallback)
- Initials avatar (no image — renders initials)
- Circular shape
- Four sizes: `sm` (24px), `md` (32px, default), `lg` (48px), `xl` (64px)
- Optional status indicator dot (positioned bottom-right, 3 colors: online/away/busy)
- Grouped avatars — stacked overlap with overflow count
- `alt` attribute on image

### Out of Scope

- Square variant — circular only; square is a separate pattern (thumbnail)
- Upload/change functionality — consumer handles that
- Badge count overlay — use Badge component positioned separately

## Interfaces

### SCSS: `resources/sass/components/_avatar.scss`

```scss
@use '../tokens/colors' as *;
@use '../tokens/spacing' as *;
@use '../tokens/typography' as *;
@use '../tokens/borders' as *;
@use '../tokens/shadows' as *;

.avatar {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: $radius-full;
    overflow: hidden;
    flex-shrink: 0;
    font-weight: $font-weight-semibold;
    color: $color-white;
    background: $color-primary;
    user-select: none;

    // Sizes
    &--sm {
        width: 1.5rem;
        height: 1.5rem;
        font-size: $font-size-xs;
    }

    &--md {
        width: 2rem;
        height: 2rem;
        font-size: $font-size-sm;
    }

    &--lg {
        width: 3rem;
        height: 3rem;
        font-size: $font-size-lg;
    }

    &--xl {
        width: 4rem;
        height: 4rem;
        font-size: $font-size-xl;
    }
}

.avatar__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.avatar__initials {
    line-height: 1;
    text-transform: uppercase;
}

// Status indicator dot
.avatar__status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 0.375rem;
    height: 0.375rem;
    border-radius: $radius-full;
    border: 1.5px solid $color-white;

    .avatar--sm & {
        width: 0.25rem;
        height: 0.25rem;
        border-width: 1px;
    }

    .avatar--lg &,
    .avatar--xl & {
        width: 0.5rem;
        height: 0.5rem;
    }

    &--online {
        background: $color-success;
    }

    &--away {
        background: $color-warning;
    }

    &--busy {
        background: $color-danger;
    }
}

// Grouped avatars
.avatar-group {
    display: inline-flex;
    flex-direction: row-reverse;  // right-to-left stacking: first avatar on top

    .avatar {
        border: 2px solid $color-white;

        & + .avatar {
            margin-right: -0.375rem;
        }

        .avatar--sm + .avatar--sm {
            margin-right: -0.25rem;
        }

        .avatar--lg + .avatar--lg,
        .avatar--xl + .avatar--xl {
            margin-right: -0.5rem;
        }
    }

    &--left {
        flex-direction: row;  // left-to-right: last avatar on top
    }
}

// Overflow count rendered as an avatar-like circle
.avatar-group__count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: $radius-full;
    flex-shrink: 0;
    font-size: $font-size-xs;
    font-weight: $font-weight-semibold;
    background: $color-bg-alt;
    color: $color-text-muted;
    border: 2px solid $color-white;

    // Match avatar sizes
    width: 2rem;
    height: 2rem;

    .avatar-group .avatar--sm + &,
    .avatar-group & {
        width: 1.5rem;
        height: 1.5rem;
        font-size: 0.625rem;
        margin-right: -0.25rem;
    }

    .avatar-group .avatar--lg + &,
    .avatar-group .avatar--lg ~ & {
        width: 3rem;
        height: 3rem;
        font-size: $font-size-sm;
        margin-right: -0.5rem;
    }

    .avatar-group .avatar--xl + &,
    .avatar-group .avatar--xl ~ & {
        width: 4rem;
        height: 4rem;
        font-size: $font-size-md;
        margin-right: -0.5rem;
    }
}
```

### Blade Component

**`resources/views/components/avatar.blade.php`:**

```blade
@props([
    'src' => null,
    'alt' => '',
    'initials' => '',
    'size' => 'md',  // sm | md | lg | xl
    'status' => null,  // online | away | busy
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
```

**`resources/views/components/avatar-group.blade.php`:**

```blade
@props([
    'avatars' => [],
    'size' => 'md',
    'max' => 3,
    'direction' => 'right',  // right (RTL stack) | left (LTR stack)
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
```

### Usage

```blade
{{-- Image avatar --}}
<x-avatar src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" size="sm" />

{{-- Initials fallback --}}
<x-avatar initials="JD" size="lg" />

{{-- With status indicator --}}
<x-avatar initials="JD" size="md" status="online" />

{{-- In nav user menu --}}
<x-dropdown placement="right">
    <x-slot:trigger>
        <x-avatar src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" size="sm" />
        {{ auth()->user()->name }}
    </x-slot:trigger>
    <x-dropdown-link href="/profile">Profile</x-dropdown-link>
    <x-dropdown-link href="/logout">Logout</x-dropdown-link>
</x-dropdown>

{{-- Author photo --}}
<x-avatar src="{{ $author->photo_url }}" alt="{{ $author->name }}" size="xl" />

{{-- Grouped avatars --}}
<x-avatar-group :avatars="[
    ['src' => '/users/alice.jpg', 'alt' => 'Alice'],
    ['src' => '/users/bob.jpg', 'alt' => 'Bob'],
    ['src' => '/users/charlie.jpg', 'alt' => 'Charlie'],
    ['src' => '/users/diana.jpg', 'alt' => 'Diana'],
    ['initials' => 'EV', 'alt' => 'Eve'],
]" :max="3" size="sm" />

{{-- Right-aligned (default) vs left-aligned --}}
<x-avatar-group :avatars="$participants" direction="left" />
```

## Composition Rules

| Can contain (avatar) | Can contain inside |
|---|---|
| `<img>` (when src provided) | Nothing else — avatar is atomic |
| `<span>` initials (fallback) | — |
| Status dot `<span>` | — |

| Cannot contain (avatar) | Cannot be inside (avatar) |
|---|---|
| Text or labels | Text (use beside avatar, not in it) |
| Icons | Badge (position separately) |
| Buttons | Interactive elements (wrap avatar in a button if needed) |

| Can contain (avatar-group) |
|---|
| Avatar components (via array data) |
| Overflow count badge |

| Cannot contain (avatar-group) |
|---|
| Non-avatar elements |
| Interactive elements inside the overflow count |
| Text or labels |
| Avatar components passed as slot content (use data array only) |

## Accessibility

- `role="img"` on the avatar container
- `aria-label` set to `alt` or `initials` text
- Image avatar uses `<img alt="">` for native fallback
- Status indicator has `aria-label` describing the status
- Status dot is purely decorative alongside the label (color is not the only indicator)
- Avatar group has `role="group"` and `aria-label="Users"`
- Overflow count has `aria-label="N more"` with `title` attribute

## Acceptance Criteria

1. `_avatar.scss` compiles without errors
2. Image avatar renders `<img>` inside circular container
3. Initials avatar renders uppercase initials on colored background
4. Four sizes (`sm`/`md`/`lg`/`xl`) produce correct dimensions
5. Status dot renders at bottom-right corner in correct color
6. Status dot scales proportionally with avatar size
7. `role="img"` and `aria-label` are present on single avatar
8. No avatar renders when neither `src` nor `initials` provided (empty initials)
9. `avatar-group` renders avatars in overlapping stack with `flex-direction: row-reverse`
10. `max` limits visible avatars; overflow shows as `+N` count circle
11. `direction="left"` changes stack direction to `flex-direction: row`
12. Overflow count has `aria-label` with remaining count
13. Avatar group has `role="group"` and `aria-label="Users"`
