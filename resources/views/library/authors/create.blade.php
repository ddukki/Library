@extends('layouts.library')

@section('content')
    <div style="margin-top: 1.5rem">
        @include('library.authors._form', ['author' => false])
    </div>
@endsection
