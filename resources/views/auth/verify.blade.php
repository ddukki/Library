@extends('layouts.app')

@section('content')
<div class="container container--sm" style="margin-top: 3rem">
    <x-card>
        <x-slot:header>{{ __('Verify Your Email Address') }}</x-slot:header>

        @if (session('resent'))
            <x-alert variant="success">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </x-alert>
        @endif

        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
    </x-card>
</div>
@endsection
