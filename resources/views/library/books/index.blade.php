@extends('layouts.library')

@section('content')
	<div class="container">
	    <div class="row justify-content-center">
			<all-books
                :initial-search-term="{{ json_encode($searchTerm ?? '') }}"
                :initial-search-column="{{ json_encode($searchColumn ?? []) }}">
			</all-books>
		</div>
	</div>
@endsection
