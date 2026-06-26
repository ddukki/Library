# Nav

## Purpose

Primary navigation: top navbar, sidebar menu, and context navigation. Supports horizontal and vertical orientations, active state indicators, responsive collapse, and Dropdown integration for user menus.

## Variants

| Variant | Prop | Description |
|---------|------|-------------|
| Light | `variant="light"` | White background (default) |
| Dark | `variant="dark"` | Primary color background, inverse text |
| Sticky | `sticky` | `position: sticky; top: 0` |

### Orientations

| Orientation | Prop | Description |
|-------------|------|-------------|
| Horizontal | `orientation="horizontal"` | Default top navbar |
| Vertical | `orientation="vertical"` | Sidebar menu |

### Sub-Components

- `<x-nav-link>` — individual nav item with `active` state and optional `icon`
- `<x-nav-brand>` — site logo/name link

## Do

```blade
<x-nav>
    <x-slot:brand>
        <x-nav-brand href="/">Library</x-nav-brand>
    </x-slot:brand>

    <x-slot:links>
        <x-nav-link href="/home" :active="request()->routeIs('home')">Home</x-nav-link>
        <x-nav-link href="/books" :active="request()->routeIs('books.*')">Books</x-nav-link>
    </x-slot:links>

    <x-slot:end>
        <x-dropdown placement="right">
            <x-slot:trigger>{{ auth()->user()->name }}</x-slot:trigger>
            <x-dropdown-link href="/profile">Profile</x-dropdown-link>
        </x-dropdown>
    </x-slot:end>
</x-nav>
```

## Don't

```blade
{{-- Don't put cards or forms inside nav --}}
<x-nav>
    <x-card>Wrong</x-card>
</x-nav>
```

## Composition Rules

| Can contain | Can be inside |
|-------------|---------------|
| Brand slot (logo/site name) | Page header / layout shell |
| Links slot (nav-link items) | Any page container |
| End slot (user menu, actions) | — |

| Cannot contain | Cannot be inside |
|----------------|------------------|
| Cards, forms, page content | Card body |
| Alert or badge directly | Modal body |
| Multiple navs in one | Dropdown menu |

## Accessibility

- Root is `<nav>` with implicit `navigation` landmark role
- Active links get `aria-current="page"`
- Toggle button has `aria-label="Toggle navigation"` and `aria-expanded`
- Links are wrapped in `<li>` inside `<ul>`

## Responsive Behavior

- Horizontal nav collapses at `md` breakpoint (768px)
- Toggle button hidden above `md`, visible below
- Collapse wrapper switches to column layout when open on mobile
- Vertical nav does not collapse
