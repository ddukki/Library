@extends('layouts.library')

@section('content')
    <div class="page-content">
        @include('library.authors._form', ['author' => false])
    </div>
@endsection
