@extends('layouts.app')

@section('content')
<div class="container container--sm" style="margin-top: 3rem">
    <x-card>
        <x-slot:header>{{ __('Reset Password') }}</x-slot:header>

        @if (session('status'))
            <x-alert variant="success">
                {{ session('status') }}
            </x-alert>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <x-form-input name="email" type="email" label="{{ __('E-Mail Address') }}"
                :value="old('email')" required autofocus />

            <div style="margin-top: 1rem">
                <x-button type="submit">{{ __('Send Password Reset Link') }}</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
