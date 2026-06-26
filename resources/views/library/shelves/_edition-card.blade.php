<div style="margin-bottom: 1rem">
    <x-card href="{{ route('editions.show', ['edition' => $edition->id]) }}">
        <div style="text-align: center">
            <div>{{ $edition->book->title }}</div>
            <div class="small">
                {{ $edition->book->authors->map(fn($a) => collect([$a->first_name, $a->middle_name, $a->last_name])->filter()->join(' '))->join(', ') }}
            </div>
            <div>
                <h1 style="margin-top: 0.75rem"><i class="fas fa-book"></i></h1>
            </div>
            <div class="small">{{ $shelf->name }} Edition</div>
        </div>
    </x-card>
</div>
