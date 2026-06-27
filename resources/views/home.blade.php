@extends('layouts.library')

@section('content')
<div class="container">
    <div class="flex flex--center" style="flex-wrap: wrap; gap: 2rem; margin-bottom: 3rem">
        <div style="flex: 1; text-align: center">
            <h2>Browse Books</h2>
            <form method="POST" action="{{ route('books.all') }}">
                @csrf
                <input hidden value="title" name="searchColumn[]">
                <div class="input-group mb-md" style="justify-content: center">
                    <input type="text" class="form-input__field"
                            name="searchTerm"
                            placeholder="Search Books"
                            aria-label="Search for books">
                    <div class="input-group__append">
                        <x-button type="submit">
                            <i class="fas fa-search"></i>
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
        <div style="flex: 1; text-align: center">
            <h2>Browse Authors</h2>
            <form method="POST" action="{{ route('authors.all') }}">
                @csrf
                <input hidden value="first_name" name="searchColumn[]">
                <input hidden value="middle_name" name="searchColumn[]">
                <input hidden value="last_name" name="searchColumn[]">
                <div class="input-group mb-md" style="justify-content: center">
                    <input type="text" class="form-input__field"
                            name="searchTerm"
                            placeholder="Search Authors"
                            aria-label="Search for authors">
                    <div class="input-group__append">
                        <x-button type="submit">
                            <i class="fas fa-search"></i>
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div style="margin-bottom: 3rem">
        @include('library.shelves._shelf-manager')
    </div>
    <div>
        <x-card>
            <x-slot:header>Admin Settings</x-slot:header>

            <a href="{{ route('extenttypes.index') }}">
                Manage Location Types
            </a>
        </x-card>
    </div>
</div>
@endsection
