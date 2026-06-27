@extends('layouts.library')

@section('content')
<div x-data="bookForm(null, @js($authors))" class="container">
    <div class="split-layout">
        <div>
            <div class="form-card">
                <div class="form-card__header">
                    <span class="form-card__header-title">Book Details</span>
                </div>
                <div class="form-card__body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-group__label form-group__required">Title</label>
                            <input type="text" class="form-input__field"
                                    placeholder="Enter book title"
                                    x-model="book.title">
                        </div>
                        <div class="form-group">
                            <label class="form-group__label">ISBN</label>
                            <input type="text" class="form-input__field"
                                    placeholder="978-0-000-00000-0"
                                    x-model="book.isbn">
                        </div>
                        <div class="form-group">
                            <label class="form-group__label">Language</label>
                            <input type="text" class="form-input__field"
                                    placeholder="English"
                                    x-model="book.language">
                        </div>
                        <div class="form-group">
                            <label class="form-group__label">Page Count</label>
                            <input type="number" class="form-input__field"
                                    placeholder="0"
                                    x-model="book.page_count">
                        </div>
                        <div class="form-group form-grid__full">
                            <label class="form-group__label">Description</label>
                            <textarea class="form-input__field"
                                    placeholder="Brief description of the book..."
                                    x-model="book.description"></textarea>
                        </div>
                    </div>
                    <div class="form-actions form-actions--right">
                        <span x-show="!editing">
                            <x-button variant="primary" x-on:click.prevent="createBook">Save Book</x-button>
                        </span>
                        <span x-show="editing">
                            <x-button variant="primary" x-on:click.prevent="updateBook">Update Book</x-button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="sidebar-card">
                <div class="sidebar-card__title">Authors</div>
                <p x-show="book.authors.length == 0" class="sidebar-card__hint">
                    Select authors from the list below.
                </p>
                <div class="sidebar-card__badges">
                    <template x-for="(selected, index) in book.authors" :key="index">
                        @include('library.authors._selected-author-badge', ['item' => 'selected'])
                    </template>
                </div>

                <div class="form-group">
                    <input type="text" class="form-input__field"
                            placeholder="Search authors..."
                            aria-label="Search Available Authors"
                            x-model="searchTerm"
                            x-on:keyup.enter="searchAuthors">
                </div>
                <x-button variant="secondary" size="sm" x-on:click.prevent="searchAuthors" class="sidebar-card__search-btn">
                    <i class="fas fa-search"></i> Search
                </x-button>

                <div class="sidebar-card__list">
                    <table class="table sidebar-card__table">
                        <thead>
                            <th scope="col"></th>
                            <th scope="col">Author</th>
                        </thead>
                        <template x-for="(author, index) in authors" :key="index">
                            @include('library.authors._author-select-row', ['item' => 'author'])
                        </template>
                    </table>
                </div>
                @include('library.partials._pagination')
            </div>
        </div>
    </div>
</div>
@endsection
