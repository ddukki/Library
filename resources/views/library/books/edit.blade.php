@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <book-add :edit-book="{{ json_encode($book) }}">
            </book-add>
        </div>
    </div>
@endsection
