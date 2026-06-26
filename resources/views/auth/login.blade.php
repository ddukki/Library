@extends('layouts.app')

@section('content')
<div class="container container--sm" style="margin-top: 3rem">
    <x-card>
        <x-slot:header>{{ __('Login') }}</x-slot:header>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <x-form-input name="email" type="email" label="{{ __('E-Mail Address') }}"
                :value="old('email')" required autocomplete="email" autofocus />

            <x-form-input name="password" type="password" label="{{ __('Password') }}"
                required autocomplete="current-password" />

            <x-form-input name="remember" type="checkbox" label="{{ __('Remember Me') }}"
                {{ old('remember') ? 'checked' : '' }} />

            <div class="flex flex--gap-sm" style="margin-top: 1rem">
                <x-button type="submit">{{ __('Login') }}</x-button>

                @if (Route::has('password.request'))
                    <x-button variant="link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </x-button>
                @endif
            </div>
        </form>
    </x-card>
</div>
@endsection
