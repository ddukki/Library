@extends('layouts.app')

@section('content')
<div class="container container--sm" style="margin-top: 3rem">
    <x-card>
        <x-slot:header>{{ __('Register') }}</x-slot:header>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <x-form-input name="name" label="{{ __('Name') }}"
                :value="old('name')" required autocomplete="name" autofocus />

            <x-form-input name="email" type="email" label="{{ __('E-Mail Address') }}"
                :value="old('email')" required autocomplete="email" />

            <x-form-input name="password" type="password" label="{{ __('Password') }}"
                required autocomplete="new-password" />

            <x-form-input name="password_confirmation" type="password" label="{{ __('Confirm Password') }}"
                required autocomplete="new-password" />

            <div style="margin-top: 1rem">
                <x-button type="submit">{{ __('Register') }}</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
