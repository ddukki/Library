@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <book-form :edit-book="false"
                    :initial-authors="{{ json_encode($authors) }}">
            </book-form>
        </div>
    </div>
@endsection
