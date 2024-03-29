@extends('layouts.library')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-3">
                <h2>{{ $book->title }}</h2>
                <h5>
                    @php
                        $authors = [];
                        foreach($book->authors as $author) {
                            array_push($authors, $author->first_name.' '.$author->middle_name.' '.$author->last_name);
                        }
                        $authorList = implode($authors, ', ');
                    @endphp
                    {{ $authorList }}
                </h5>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Book Information
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        Editions
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <book-editions
                                    :book="{{ json_encode($book) }}">
                            </book-editions>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
