@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <shelf-form :edit-shelf="{{ json_encode($shelf) }}">
            </shelf-form>
        </div>
    </div>
@endsection
