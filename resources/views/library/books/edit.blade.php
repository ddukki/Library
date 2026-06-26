@extends('layouts.library')

@section('content')
    <div x-data="bookForm(@js($book), @js($authors))">
        <div class="container">
            <div class="form-group">
                <x-card compact>
                    <x-slot:header>Book Information</x-slot:header>

                    <input id="title" name="title"
                            type="text" class="form-input__field"
                            placeholder="Book Title"
                            x-model="book.title">
                </x-card>
            </div>
            <div class="form-group">
                <x-card compact>
                    <x-slot:header>Book Author(s)</x-slot:header>

                    <p x-show="book.authors.length == 0">
                        Select authors for the book from the list of <b>Available Authors</b>!
                    </p>
                    <h3>
                        <template x-for="(selected, index) in book.authors" :key="index">
                            @include('library.authors._selected-author-badge', ['item' => 'selected'])
                        </template>
                    </h3>
                    <div class="input-group" style="margin-bottom: 1rem">
                        <input type="text" class="form-input__field"
                                x-model="searchTerm"
                                x-on:keyup.enter="searchAuthors"
                                placeholder="Search Available Authors">
                        <div class="input-group__append">
                            <x-button variant="secondary" x-on:click.prevent="searchAuthors">
                                <i class="fas fa-search"></i>
                            </x-button>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <th scope="col"></th>
                            <th scope="col">Author Name</th>
                            <th scope="col">Books</th>
                        </thead>
                        <template x-for="(author, index) in authors" :key="index">
                            @include('library.authors._author-select-row', ['item' => 'author'])
                        </template>
                    </table>

                    <x-slot:footer>
                        @include('library.partials._pagination')
                    </x-slot:footer>
                </x-card>
            </div>
            <div class="form-group">
                <x-button x-show="!editing" variant="primary" size="lg" x-on:click.prevent="createBook">
                    Create Book
                </x-button>
                <x-button x-show="editing" variant="primary" size="lg" x-on:click.prevent="updateBook">
                    Update Book
                </x-button>
            </div>
        </div>
    </div>
@endsection
