@extends('layouts.library')

@section('content')
	<div class="container">
	    <div class="row justify-content-center">
			<shelf-books :shelf="{{ json_encode($shelf) }}">
			</shelf-books>
		</div>
	</div>
@endsection
