@extends('layouts.library')

@section('content')
    <div class="container" style="margin-top: 1.5rem">
        <x-card>
            <x-slot:header>
                <h5>{{ $edition->book->title }}</h5>
                <i>{{ $edition->name }} Edition</i>
            </x-slot:header>

            <div style="margin-bottom: 1.5rem">
                @include('library.editions.progress')
            </div>

            @include('library.editions.quotes')
        </x-card>
    </div>
@endsection

@section('body-scripts')
    @parent
@endsection
