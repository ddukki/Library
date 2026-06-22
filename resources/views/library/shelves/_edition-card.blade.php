<div class="col-12 mb-3">
    <a class="card" href="{{ route('editions.show', ['edition' => $edition->id]) }}">
        <div class="card text-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">{{ $edition->book->title }}</div>
                    <div class="col-12 small">
                        {{ $edition->book->authors->map(fn($a) => collect([$a->first_name, $a->middle_name, $a->last_name])->filter()->join(' '))->join(', ') }}
                    </div>
                    <div class="col-12">
                        <h1 class="mt-3"><i class="fas fa-book"></i></h1>
                    </div>
                    <div class="col-12 small">{{ $shelf->name }} Edition</div>
                </div>
            </div>
        </div>
    </a>
</div>
