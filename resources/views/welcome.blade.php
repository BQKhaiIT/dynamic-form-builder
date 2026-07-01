@extends('layouts.guest')

@section('content')
    <div class="text-center">
        <span class="badge text-bg-primary-subtle text-primary mb-3">Enterprise-ready Laravel workflow</span>
        <h2 class="h3 fw-bold mb-3">Build and submit dynamic forms with clarity</h2>
        <p class="text-muted mb-4">
            Admins can create forms, manage fields, and control publication status. Employees can browse active forms and submit structured responses.
        </p>

        <div class="d-grid gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Open Workspace</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary">Create Employee Account</a>
            @endauth
        </div>
    </div>
@endsection
