<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @routes

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <x-nav brand="{{ config('app.name', 'Laravel') }}" brand-url="{{ url('/') }}">
            <x-slot:links>
            </x-slot:links>

            <x-slot:end>
                @guest
                    <a class="nav__link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    @if (Route::has('register'))
                        <a class="nav__link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endif
                @else
                    <x-dropdown placement="right">
                        <x-slot:trigger>{{ Auth::user()->name }}</x-slot:trigger>

                        <x-dropdown-link href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </x-dropdown-link>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </x-dropdown>
                @endguest
            </x-slot:end>
        </x-nav>

        <main style="padding-top: 1.5rem; padding-bottom: 1.5rem">
            @yield('content')
        </main>
    </div>
</body>
</html>
