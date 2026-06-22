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

	    @vite(['resources/sass/app.scss', 'resources/sass/library.scss', 'resources/js/app.js'])
	@show

</head>
<body>
    <div id="app">
		@include('layouts.nav')
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
