@extends('layouts.library')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
            <h2>Browse Books</h2>
            <form class="form" method="POST" action="{{ route('books.all') }}">
                @csrf
                <input hidden value="title" name="searchColumn[]">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control"
                                name="searchTerm"
                                placeholder="Search Books"
                                aria-label="Search for books">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-6 text-center mb-5">
            <h2>Browse Authors</h2>
            <form class="form" method="POST" action="{{ route('authors.all') }}">
                @csrf
                <input hidden value="first_name" name="searchColumn[]">
                <input hidden value="last_name" name="searchColumn[]">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control"
                                name="searchTerm"
                                placeholder="Search Authors"
                                aria-label="Search for authors">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <shelf-manager>
        </shelf-manager>
    </div>
</div>
@endsection
