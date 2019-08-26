@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <book-add :edit-book="false"
                    :initial-authors="{{ json_encode($authors) }}">
            </book-add>
        </div>
    </div>
@endsection
