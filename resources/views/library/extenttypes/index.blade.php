@extends('layouts.library')

@section('content')
<div class="container page-content">
    <div class="form-card">
        <div class="form-card__header">
            <h2 class="form-card__header-title">Extent Types</h2>
        </div>
        <div class="form-card__body">
            @include('library.extenttypes._list')
        </div>
    </div>
</div>
@endsection
