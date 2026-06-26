<div>
    <div style="text-align: center; margin-bottom: 3rem">
        <h2>{{ $shelf->name }}</h2>
        <x-card compact>
            <div style="text-align: center">
                <a href="{{ route('shelves.edit', ['shelf' => $shelf->id]) }}">
                    <i class="fas fa-edit"></i> Edit Shelf
                </a>
            </div>
        </x-card>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem">
        <a href="{{ route('books.index') }}" style="display: block; height: 100%">
            <x-card>
                <div style="text-align: center">
                    <p>Add New Book</p>
                    <h1 style="margin-top: 0.75rem"><i class="fas fa-plus"></i></h1>
                </div>
            </x-card>
        </a>
        @foreach ($shelf->editions as $edition)
            @include('library.shelves._edition-card', ['edition' => $edition, 'shelf' => $shelf])
        @endforeach
    </div>
</div>
