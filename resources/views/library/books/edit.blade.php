@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12"
                 x-data="bookForm(@js($book), @js($authors))">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header">Book Information</div>
                                <div class="card-body">
                                    <input id="title" name="title"
                                            type="text" class="form-control"
                                            placeholder="Book Title"
                                            x-model="book.title">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header">Book Author(s)</div>
                                <div class="card-body">
                                    <p x-show="book.authors.length == 0">
                                        Select authors for the book from the list of <b>Available Authors</b>!
                                    </p>
                                    <h3>
                                        <template x-for="(selected, index) in book.authors" :key="index">
                                            @include('library.authors._selected-author-badge', ['item' => 'selected'])
                                        </template>
                                    </h3>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                                x-model="searchTerm"
                                                x-on:keyup.enter="searchAuthors"
                                                placeholder="Search Available Authors">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" x-on:click.prevent="searchAuthors">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                            <th scope="col"></th>
                                            <th scope="col">Author Name</th>
                                            <th scope="col">Books</th>
                                        </thead>
                                        <template x-for="(author, index) in authors" :key="index">
                                            @include('library.authors._author-select-row', ['item' => 'author'])
                                        </template>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    @include('library.partials._pagination')
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="form-group col-12">
                                    <button x-show="!editing"
                                            class="btn btn-primary btn-lg"
                                            x-on:click.prevent="createBook">
                                        Create Book
                                    </button>
                                    <button x-show="editing"
                                            class="btn btn-primary btn-lg"
                                            x-on:click.prevent="updateBook">
                                        Update Book
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
