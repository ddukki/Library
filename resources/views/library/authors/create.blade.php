@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            @include('library.authors._form', ['author' => false])
        </div>
    </div>
@endsection
