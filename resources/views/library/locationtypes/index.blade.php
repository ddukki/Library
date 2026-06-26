@extends('layouts.library')

@section('content')
    <div class="container" style="margin-top: 1.5rem">
        <div class="flex flex--center" style="flex-direction: column">
            @include('library.locationtypes._list')
        </div>
    </div>
@endsection
