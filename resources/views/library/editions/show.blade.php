@extends('layouts.library')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $edition->book->title }}</h5>
                        <i>{{ $edition->name }} Edition</i>
                    </div>
                    <div class="card-body border-bottom">
                        @include('library.editions.progress')
                    </div>
                    <div class="card-body">
                        @include('library.editions.quotes')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body-scripts')
    @parent
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection
