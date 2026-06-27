@extends('layouts.library')

@section('content')
<div class="container page-content">
    <div class="detail-header">
        <div>
            <h1 class="detail-title">{{ $edition->book->title }}</h1>
            <div class="detail-meta">
                <span>{{ $edition->name }} Edition</span>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <div class="detail-section__body">
            @include('library.editions.progress')
        </div>
    </div>

    <div class="detail-section">
        <div class="detail-section__header">
            <h3 class="detail-section__title">Quotes</h3>
        </div>
        <div class="detail-section__body">
            @include('library.editions.quotes')
        </div>
    </div>
</div>
@endsection

@section('body-scripts')
    @parent
@endsection
