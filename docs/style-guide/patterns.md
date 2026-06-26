# Common UI Patterns

## CRUD List Page

Layout for listing, creating, and deleting records:

- Container → Nav → Header (title + Create button) → Table with action dropdowns → Pagination

```blade
<x-container>
    <x-nav><!-- tabs/filters --></x-nav>

    <div class="flex flex--between">
        <h1 class="heading--2">Authors</h1>
        <x-button href="{{ route('authors.create') }}">Create</x-button>
    </div>

    <x-table striped hover>
        <x-slot:header>
            <th class="table__th">Name</th>
            <th class="table__th table__th--right">Actions</th>
        </x-slot:header>
        <x-slot:body>
            @foreach ($authors as $author)
                <tr>
                    <td class="table__td">{{ $author->name }}</td>
                    <td class="table__td table__td--right">
                        <x-dropdown placement="right">
                            <x-slot:trigger>
                                <x-button variant="secondary" size="sm">Actions</x-button>
                            </x-slot:trigger>
                            <x-dropdown-link href="{{ route('authors.edit', $author) }}">Edit</x-dropdown-link>
                            <form method="POST" action="{{ route('authors.destroy', $author) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <x-dropdown-button onclick="return confirm('Are you sure?')">Delete</x-dropdown-button>
                            </form>
                        </x-dropdown>
                    </td>
                </tr>
            @endforeach
        </x-slot:body>
    </x-table>

    <x-pagination :paginator="$authors" />
</x-container>
```

## Form with Validation

Layout for forms with server-side validation:

- Card → Card header (title) → Form → Inputs → Alert (errors) → Card footer (buttons)

```blade
<x-card>
    <x-slot:header>
        <h2 class="heading--3">Edit Book</h2>
    </x-slot:header>

    <form method="POST" action="{{ route('books.update', $book) }}">
        @csrf @method('PUT')

        @if($errors->any())
            <x-alert variant="danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <x-form-input name="title" label="Title" :value="$book->title" required />
        <x-form-input name="isbn" label="ISBN" :value="$book->isbn" />

        <x-slot:footer>
            <div class="flex flex--gap-sm" style="justify-content: flex-end">
                <x-button variant="secondary" href="{{ route('books.index') }}">Cancel</x-button>
                <x-button type="submit">Save</x-button>
            </div>
        </x-slot:footer>
    </form>
</x-card>
```

## Modal Confirmation

Pattern for delete/action confirmations:

```blade
<button @click="$dispatch('open-modal', 'confirm-delete')">
    Delete
</button>

<x-modal name="confirm-delete" title="Delete Book" size="sm">
    Are you sure you want to delete <strong>{{ $book->title }}</strong>?
    This action cannot be undone.

    <x-slot:footer>
        <x-button variant="secondary" @click="open = false">Cancel</x-button>
        <form method="POST" action="{{ route('books.destroy', $book) }}" style="display:inline">
            @csrf @method('DELETE')
            <x-button variant="danger" type="submit">Delete</x-button>
        </form>
    </x-slot:footer>
</x-modal>
```

## Nav with User Dropdown

Header layout with brand, nav links, and user menu:

```blade
<x-nav sticky>
    <x-slot:brand>
        <x-nav-brand href="/">Library</x-nav-brand>
    </x-slot:brand>

    <x-slot:links>
        <x-nav-link href="/" :active="request()->routeIs('home')">Home</x-nav-link>
        <x-nav-link href="{{ route('books.index') }}" :active="request()->routeIs('books.*')">Books</x-nav-link>
        <x-nav-link href="{{ route('authors.index') }}" :active="request()->routeIs('authors.*')">Authors</x-nav-link>
    </x-slot:links>

    <x-slot:end>
        @auth
            <x-dropdown placement="right">
                <x-slot:trigger>
                    <x-avatar initials="{{ substr(auth()->user()->name, 0, 2) }}" size="sm" />
                    {{ auth()->user()->name }}
                </x-slot:trigger>
                <x-dropdown-link href="/profile">Profile</x-dropdown-link>
                <div class="dropdown__divider"></div>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <x-dropdown-button type="submit">Logout</x-dropdown-button>
                </form>
            </x-dropdown>
        @endauth
    </x-slot:end>
</x-nav>
```

## Tabbed Detail View

Layout for showing related content sections on a detail page:

```blade
<div class="flex flex--gap-md" style="justify-content: space-between; align-items: center">
    <h1 class="heading--2">{{ $book->title }}</h1>
    <x-button href="{{ route('books.edit', $book) }}">Edit</x-button>
</div>

<x-tabs active="details">
    <x-slot:tabs>
        <x-tab name="details" label="Details" />
        <x-tab name="editions" label="Editions" />
        <x-tab name="quotes" label="Quotes" />
    </x-slot:tabs>

    <x-tab-panel name="details">
        <dl>
            <dt>Author</dt>
            <dd>{{ $book->author->name }}</dd>
            <dt>ISBN</dt>
            <dd>{{ $book->isbn }}</dd>
        </dl>
    </x-tab-panel>

    <x-tab-panel name="editions">
        <x-table striped compact>
            <x-slot:header>
                <th class="table__th">Edition</th>
                <th class="table__th">Publisher</th>
                <th class="table__th table__th--right">Actions</th>
            </x-slot:header>
            <x-slot:body>
                @foreach($book->editions as $edition)
                    <tr>
                        <td class="table__td">{{ $edition->name }}</td>
                        <td class="table__td">{{ $edition->publisher }}</td>
                        <td class="table__td table__td--right">
                            <x-button variant="secondary" size="sm">Edit</x-button>
                        </td>
                    </tr>
                @endforeach
            </x-slot:body>
        </x-table>
    </x-tab-panel>

    <x-tab-panel name="quotes">
        <x-list style="divided">
            @forelse($book->quotes as $quote)
                <x-list-item>"{{ $quote->text }}"</x-list-item>
            @empty
                <p class="body--sm text--muted">No quotes yet.</p>
            @endforelse
        </x-list>
    </x-tab-panel>
</x-tabs>
```
