@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <book-form :edit-book="{{ json_encode($book) }}">
            </book-form>
        </div>
    </div>
@endsection
