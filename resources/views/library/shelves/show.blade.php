@extends('layouts.library')

@section('content')
<div class="container page-content">
    <div class="detail-header">
        <div>
            <h1 class="detail-title">{{ $shelf->name }}</h1>
            <div class="detail-meta">
                <span><i class="fas fa-book"></i> {{ $shelf->editions_count ?? $shelf->editions->count() }} editions</span>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem">
            <x-button href="{{ route('shelves.edit', $shelf) }}" variant="secondary">
                <i class="fas fa-edit"></i> Edit
            </x-button>
        </div>
    </div>

    @include('library.shelves._books')
</div>
@endsection
