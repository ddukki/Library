<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

	@routes

	@section('header-styles')
		@routes

	    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
	@show

</head>
<body>
    <div id="app">
		@include('layouts.nav')
        <main style="padding-top: 1.5rem; padding-bottom: 1.5rem">
            @yield('content')
        </main>
    </div>
</body>
</html>
