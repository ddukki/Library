@extends('layouts.library')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="container-fluid"
                     x-data="allAuthors(@js($searchTerm ?? ''), @js($searchColumn ?? []))">
                    <div class="row">
                        <div class="col-10">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"
                                        placeholder="Search Authors"
                                        aria-label="Search for authors"
                                        x-model="searchTerm">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" x-on:click.prevent="search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <a x-bind:href="route('authors.create')"
                                    class="btn btn-primary"
                                    role="button">
                                <i class="fas fa-plus"></i> Add New
                            </a>
                        </div>
                    </div>
                    <template x-for="(author, index) in authors" :key="index">
                        @include('library.authors._author-card', ['item' => 'author'])
                    </template>
                    <div class="row">
                        <div class="col-12">
                            @include('library.partials._pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
