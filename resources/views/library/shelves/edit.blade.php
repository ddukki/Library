@extends('layouts.library')

@section('content')
<div class="container page-content">
    <div class="form-card">
        <div class="form-card__header">
            <h2 class="form-card__header-title">Edit Shelf</h2>
        </div>
        <div class="form-card__body">
            @include('library.shelves._form', ['shelf' => $shelf])
        </div>
    </div>
</div>
@endsection
