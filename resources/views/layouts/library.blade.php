<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

	@routes

	@section('header-scripts')
		@include('layouts.header-scripts')
	@show

	@section('header-styles')
		<!-- Fonts -->
	    <link rel="dns-prefetch" href="//fonts.gstatic.com">
	    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

		<!-- Styles -->
	    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="{{ asset('css/library.css') }}" rel="stylesheet">
		<link href="{{ asset('css/all.css') }}" rel="stylesheet">
	@show

</head>
<body>
    <div id="app">
		@include('layouts.nav')
        <main class="py-4">
            @yield('content')
        </main>
    </div>
	@section('body-scripts')
	@show
</body>
</html>
