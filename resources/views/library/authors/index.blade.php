@extends('layouts.library')

@section('content')
    <div class="container">
        <div x-data="allAuthors(@js($searchTerm ?? ''), @js($searchColumn ?? []))">
            <div class="flex" style="align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap">
                <div style="flex: 1; min-width: 200px">
                    <div class="input-group">
                        <input type="text" class="form-input__field"
                                placeholder="Search Authors"
                                aria-label="Search for authors"
                                x-model="searchTerm">
                        <div class="input-group__append">
                            <x-button x-on:click.prevent="search">
                                <i class="fas fa-search"></i>
                            </x-button>
                        </div>
                    </div>
                </div>
                <div>
                    <x-button href="#" x-bind:href="route('authors.create')">
                        <i class="fas fa-plus"></i> Add New
                    </x-button>
                </div>
            </div>
            <template x-for="(author, index) in authors" :key="index">
                @include('library.authors._author-card', ['item' => 'author'])
            </template>
            <div>
                @include('library.partials._pagination')
            </div>
        </div>
    </div>
@endsection
