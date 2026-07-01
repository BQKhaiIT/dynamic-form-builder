@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1">Active Forms</h1>
        <p class="text-muted mb-0">Browse published forms and submit your responses.</p>
    </div>

    <div class="row g-4">
        @forelse ($forms as $form)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="badge text-bg-success">Active</span>
                        </div>
                        <h2 class="h5">{{ $form->title }}</h2>
                        <p class="text-muted flex-grow-1">{{ $form->description ?: 'No description provided.' }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">{{ $form->fields_count }} fields</span>
                            <a href="{{ route('employee.forms.show', $form) }}" class="btn btn-primary">Open Form</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">There are no active forms available right now.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $forms->links() }}
    </div>
@endsection
