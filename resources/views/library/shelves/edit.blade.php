@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            @include('library.shelves._form', ['shelf' => $shelf])
        </div>
    </div>
@endsection
