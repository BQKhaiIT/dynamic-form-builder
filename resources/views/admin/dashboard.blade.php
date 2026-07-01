@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Admin Dashboard</h1>
            <p class="text-muted mb-0">Monitor forms and submission activity.</p>
        </div>
        <a href="{{ route('admin.forms.create') }}" class="btn btn-primary">Create Form</a>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-2">Total Forms</p>
                    <h2 class="display-6 mb-0">{{ $metrics['total_forms'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-2">Active Forms</p>
                    <h2 class="display-6 mb-0">{{ $metrics['active_forms'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-2">Submissions</p>
                    <h2 class="display-6 mb-0">{{ $metrics['total_submissions'] }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
