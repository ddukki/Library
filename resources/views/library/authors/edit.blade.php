@extends('layouts.library')

@section('content')
    <div class="page-content">
        @include('library.authors._form', ['author' => $author])
    </div>
@endsection
