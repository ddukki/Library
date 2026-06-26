# Dropdown

## Purpose

Toggleable menu for user menus, action lists, and "more options" patterns. Used in nav bar (user account menu), table rows (per-item actions), and context menu patterns.

## Variants

| Variant | Prop | Description |
|---------|------|-------------|
| Left | `placement="left"` | Menu aligns to left edge (default) |
| Right | `placement="right"` | Menu aligns to right edge |
| Center | `placement="center"` | Menu centers under trigger |

### Sub-Components

| Component | Description |
|-----------|-------------|
| `<x-dropdown-link>` | `<a>` menu item |
| `<x-dropdown-button>` | `<button>` menu item |
| `dropdown__divider` | 1px separator between groups |

## Do

```blade
<x-dropdown placement="right">
    <x-slot:trigger>{{ auth()->user()->name }}</x-slot:trigger>
    <x-dropdown-link href="/profile">Profile</x-dropdown-link>
    <x-dropdown-link href="/settings">Settings</x-dropdown-link>
    <div class="dropdown__divider"></div>
    <x-dropdown-link href="/logout">Logout</x-dropdown-link>
</x-dropdown>

<x-dropdown placement="right">
    <x-slot:trigger>
        <x-button variant="secondary" size="sm">Actions</x-button>
    </x-slot:trigger>
    <x-dropdown-link href="{{ route('books.edit', $book) }}">Edit</x-dropdown-link>
    <x-dropdown-button>Duplicate</x-dropdown-button>
    <div class="dropdown__divider"></div>
    <x-dropdown-link href="{{ route('books.delete', $book) }}">Delete</x-dropdown-link>
</x-dropdown>
```

## Composition Rules

| Can contain (dropdown) | Can contain (menu) |
|------------------------|--------------------|
| Trigger slot (button, text, icon) | dropdown-link items |
| Menu slot (dropdown items only) | dropdown-button items |
| — | dropdown__divider |

| Cannot contain | Cannot be inside |
|----------------|------------------|
| Forms or form inputs | Another dropdown (nested not supported) |
| Cards or block-level components | Nav (use in `end` slot) |
| Nested dropdowns | Button (trigger is separate) |

## Accessibility

- Toggle has `role="button"`, `tabindex="0"`, `aria-haspopup="true"`, `aria-expanded`
- Menu items are `<a>` (navigation) or `<button>` (action)
- Disabled items get `aria-disabled="true"` and `tabindex="-1"`
- Alpine handles: click toggle, `@click.outside` close, Escape key close

## Responsive Behavior

Static — no responsive changes.
