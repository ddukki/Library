<div class="container-fluid">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2>{{ $shelf->name }}</h2>
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('shelves.edit', ['shelf' => $shelf->id]) }}">
                        <i class="fas fa-edit"></i> Edit Shelf
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center mb-3">
            <a class="card" href="{{ route('books.index') }}">
                <div class="card">
                    <div class="card-body">
                        <p>Add New Book</p>
                        <h1 class="mt-3"><i class="fas fa-plus"></i></h1>
                    </div>
                </div>
            </a>
        </div>
        @foreach ($shelf->editions as $edition)
            @include('library.shelves._edition-card', ['edition' => $edition, 'shelf' => $shelf])
        @endforeach
    </div>
</div>
