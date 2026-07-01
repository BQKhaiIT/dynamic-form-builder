@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Forms</h1>
            <p class="text-muted mb-0">Create, update, and publish dynamic forms.</p>
        </div>
        <a href="{{ route('admin.forms.create') }}" class="btn btn-primary">Create Form</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Fields</th>
                        <th>Submissions</th>
                        <th>Created By</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($forms as $form)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $form->title }}</div>
                                <div class="text-muted small">{{ \Illuminate\Support\Str::limit($form->description, 80) }}</div>
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $form->status === \App\Enums\FormStatus::Active ? 'success' : ($form->status === \App\Enums\FormStatus::Inactive ? 'secondary' : 'warning') }}">
                                    {{ $form->status->label() }}
                                </span>
                            </td>
                            <td>{{ $form->fields_count }}</td>
                            <td>{{ $form->submissions_count }}</td>
                            <td>{{ $form->creator->name }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.forms.show', $form) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('admin.forms.edit', $form) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </div>

                                <form method="POST" action="{{ route('admin.forms.status', $form) }}" class="d-inline-block ms-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $form->status === \App\Enums\FormStatus::Active ? \App\Enums\FormStatus::Inactive->value : \App\Enums\FormStatus::Active->value }}">
                                    <button type="submit" class="btn btn-sm btn-outline-dark">
                                        {{ $form->status === \App\Enums\FormStatus::Active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.forms.destroy', $form) }}" class="d-inline-block ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this form?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No forms available yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $forms->links() }}
    </div>
@endsection
