@extends('layouts.library')

@section('content')
<div class="container">
    @php
        $authors = [];
        foreach($book->authors as $author) {
            $authors[] = trim($author->first_name.' '.$author->middle_name.' '.$author->last_name);
        }
        $authorList = implode(', ', $authors);
    @endphp

    <div class="detail-header">
        <div>
            <div class="detail-title">{{ $book->title }}</div>
            <div class="detail-meta">
                @if($authorList)
                    <span>By {{ $authorList }}</span>
                @endif
                @if($book->page_count)
                    <span>·</span>
                    <span>{{ $book->page_count }} pages</span>
                @endif
            </div>
        </div>
        <div class="detail-actions">
            <x-button variant="secondary" href="{{ route('books.edit', $book) }}">Edit</x-button>
            <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('Delete this book?')">
                @csrf
                @method('DELETE')
                <x-button variant="danger" size="sm" type="submit">Delete</x-button>
            </form>
        </div>
    </div>

    <div class="detail-section">
        <div class="detail-section__header">
            <span class="detail-section__title">Details</span>
        </div>
        <div class="detail-section__body">
            <div class="detail-grid">
                @if($book->isbn)
                <div>
                    <div class="detail-field__label">ISBN</div>
                    <div class="detail-field__value">{{ $book->isbn }}</div>
                </div>
                @endif
                @if($book->language)
                <div>
                    <div class="detail-field__label">Language</div>
                    <div class="detail-field__value">{{ $book->language }}</div>
                </div>
                @endif
                @if($book->description)
                <div class="detail-grid__full">
                    <div class="detail-field__label">Description</div>
                    <div class="detail-field__value">{{ $book->description }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="detail-section">
        <div class="detail-section__header">
            <span class="detail-section__title">Editions</span>
            <x-button variant="secondary" size="sm" href="{{ route('editions.create', $book) }}">
                <i class="fas fa-plus"></i> Add Edition
            </x-button>
        </div>
        <div class="detail-section__body">
            @include('library.editions._editions')
        </div>
    </div>
</div>
@endsection
