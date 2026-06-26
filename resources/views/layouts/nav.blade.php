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
