@extends('layouts.library')

@section('content')
    <div class="container" style="margin-top: 1.5rem">
        <div style="text-align: center; margin-bottom: 1rem">
            <h2>{{ $book->title }}</h2>
            <h5>
                @php
                    $authors = [];
                    foreach($book->authors as $author) {
                        array_push($authors, $author->first_name.' '.$author->middle_name.' '.$author->last_name);
                    }
                    $authorList = implode(', ', $authors);
                @endphp
                {{ $authorList }}
            </h5>
        </div>
        <x-card compact>
            <x-slot:header>Book Information</x-slot:header>
            <div></div>
        </x-card>
        <div style="margin-top: 1rem">
            <x-card compact>
                <x-slot:header>Editions</x-slot:header>

                <div class="container">
                    @include('library.editions._editions')
                </div>
            </x-card>
        </div>
    </div>
@endsection
