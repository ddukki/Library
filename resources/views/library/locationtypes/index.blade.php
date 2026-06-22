@extends('layouts.library')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('library.locationtypes._list')
        </div>
    </div>
@endsection
