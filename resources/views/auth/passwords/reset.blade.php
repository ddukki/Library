@extends('layouts.app')

@section('content')
<div class="container container--sm" style="margin-top: 3rem">
    <x-card>
        <x-slot:header>{{ __('Reset Password') }}</x-slot:header>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <x-form-input name="email" type="email" label="{{ __('E-Mail Address') }}"
                :value="$email ?? old('email')" required autofocus />

            <x-form-input name="password" type="password" label="{{ __('Password') }}"
                required autocomplete="new-password" />

            <x-form-input name="password_confirmation" type="password" label="{{ __('Confirm Password') }}"
                required autocomplete="new-password" />

            <div style="margin-top: 1rem">
                <x-button type="submit">{{ __('Reset Password') }}</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
