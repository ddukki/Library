@extends('layouts.library', ['useVueRoot' => false])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('library.locationtypes._list')
        </div>
    </div>
@endsection
