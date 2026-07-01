@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $form->title }}</h1>
            <p class="text-muted mb-2">{{ $form->description ?: 'No description provided.' }}</p>
            <span class="badge text-bg-{{ $form->status === \App\Enums\FormStatus::Active ? 'success' : ($form->status === \App\Enums\FormStatus::Inactive ? 'secondary' : 'warning') }}">
                {{ $form->status->label() }}
            </span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.forms.fields.index', $form) }}" class="btn btn-outline-primary">Manage Fields</a>
            <a href="{{ route('admin.forms.edit', $form) }}" class="btn btn-primary">Edit Form</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Fields</h2>
                </div>
                <div class="card-body">
                    @forelse ($form->fields as $field)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">{{ $field->label }}</div>
                                    <div class="text-muted small">
                                        {{ $field->type->label() }} · Order {{ $field->sort_order }} · {{ $field->required ? 'Required' : 'Optional' }}
                                    </div>
                                </div>
                                <a href="{{ route('admin.forms.fields.edit', [$form, $field]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </div>

                            @if ($field->options->isNotEmpty())
                                <div class="mt-3">
                                    @foreach ($field->options as $option)
                                        <span class="badge text-bg-light border me-2">{{ $option->label }} ({{ $option->value }})</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">This form has no fields yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Metadata</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Created By</dt>
                        <dd class="col-sm-8">{{ $form->creator->name }}</dd>
                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $form->created_at?->format('M d, Y H:i') }}</dd>
                        <dt class="col-sm-4">Submissions</dt>
                        <dd class="col-sm-8">{{ $form->submissions->count() }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Recent Submissions</h2>
                </div>
                <div class="card-body">
                    @forelse ($form->submissions->take(5) as $submission)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="fw-semibold">{{ $submission->user->name }}</div>
                            <div class="text-muted small">{{ $submission->submitted_at?->format('M d, Y H:i') }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No submissions yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
