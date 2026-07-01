<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Dynamic Form Builder') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-body-tertiary">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <h1 class="h3 fw-bold text-dark mb-1">Dynamic Form Builder</h1>
                    </a>
                    <p class="text-muted mb-0">Create, manage, and submit dynamic business forms.</p>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        @if (session('status'))
                            <div class="alert alert-info">{{ session('status') }}</div>
                        @endif

                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot ?? '' }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
