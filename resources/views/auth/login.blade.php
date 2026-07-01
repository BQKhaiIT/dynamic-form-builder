@extends('layouts.guest')

@section('content')
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control">
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control">
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input id="remember" type="checkbox" name="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Remember me</label>
        </div>

        <div class="d-flex justify-content-between align-items-center">

            <button type="submit" class="btn btn-primary">Login</button>
        </div>

        <p class="text-muted small mt-4 mb-0">
            Need an account?
            <a href="{{ route('register') }}" class="text-decoration-none">Register here</a>
        </p>
    </form>
@endsection
