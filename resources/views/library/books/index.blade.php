@extends('layouts.library')

@section('content')
<div class="container">
    <div x-data="allBooks(@js($searchTerm ?? ''), @js($searchColumn ?? []))">
        <div class="index-header">
            <div>
                <h2 class="index-header__title">Books</h2>
                <p class="index-header__subtitle" x-text="books.length + ' books in your collection'"></p>
            </div>
            <div class="index-search">
                <input type="text" class="form-input__field"
                        placeholder="Search books..."
                        aria-label="Search for books"
                        x-model="searchTerm">
                <x-button x-on:click.prevent="search">
                    <i class="fas fa-search"></i>
                </x-button>
                <x-button href="#" x-bind:href="route('books.create')">
                    <i class="fas fa-plus"></i> Add Book
                </x-button>
            </div>
        </div>

        <div class="form-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Editions</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(book, index) in books" :key="index">
                        <tr>
                            <td style="font-weight: 600;" x-text="book.title"></td>
                            <td>
                                <template x-for="author in book.authors" :key="author.id">
                                    <span x-text="author.first_name + ' ' + author.last_name"></span>
                                </template>
                            </td>
                            <td><span class="tag" x-text="book.editions ? book.editions.length : 0"></span></td>
                            <td>
                                <x-button variant="ghost" size="sm" x-bind:href="route('books.edit', { book: book.id })">
                                    Edit
                                </x-button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        @include('library.partials._pagination')
    </div>
</div>
@endsection
