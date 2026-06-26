@extends('layouts.library')

@section('content')
    <div class="container">
        <div x-data="allBooks(@js($searchTerm ?? ''), @js($searchColumn ?? []))">
            <div class="flex" style="align-items: center; gap: 1rem; margin-bottom: 1.5rem">
                <div style="flex: 1; min-width: 0">
                    <div class="input-group">
                        <input type="text" class="form-input__field"
                                placeholder="Search Books"
                                aria-label="Search for books"
                                x-model="searchTerm">
                        <div class="input-group__append">
                            <x-button x-on:click.prevent="search">
                                <i class="fas fa-search"></i>
                            </x-button>
                        </div>
                    </div>
                </div>
                <div>
                    <x-button href="#" x-bind:href="route('books.create')">
                        <i class="fas fa-plus"></i> Add New
                    </x-button>
                </div>
            </div>
            <template x-for="(book, index) in books" :key="index">
                @include('library.books._book-card', ['item' => 'book'])
            </template>
            <div>
                @include('library.partials._pagination')
            </div>
        </div>
    </div>
@endsection
