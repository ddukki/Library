@extends('layouts.library')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <author-form :edit-author="{{ json_encode($author) }}">
            </author-form>
        </div>
    </div>
@endsection
