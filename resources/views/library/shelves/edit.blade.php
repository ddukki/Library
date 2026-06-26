@extends('layouts.library')

@section('content')
    <div class="container" style="margin-top: 1.5rem">
        @include('library.shelves._form', ['shelf' => $shelf])
    </div>
@endsection
