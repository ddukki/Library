@extends('layouts.library')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('library.shelves._form', ['shelf' => false])
    </div>
</div>
@endsection
