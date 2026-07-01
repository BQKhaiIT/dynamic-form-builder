@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Fields for {{ $form->title }}</h1>
            <p class="text-muted mb-0">Manage the structure and ordering of this form.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.forms.show', $form) }}" class="btn btn-outline-secondary">Back to Form</a>
            <a href="{{ route('admin.forms.fields.create', $form) }}" class="btn btn-primary">Add Field</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Label</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Order</th>
                        <th>Options</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($form->fields as $field)
                        <tr>
                            <td>{{ $field->label }}</td>
                            <td>{{ $field->type->label() }}</td>
                            <td>{{ $field->required ? 'Yes' : 'No' }}</td>
                            <td>{{ $field->sort_order }}</td>
                            <td>{{ $field->options->count() }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.forms.fields.show', [$form, $field]) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('admin.forms.fields.edit', [$form, $field]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="{{ route('admin.forms.fields.destroy', [$form, $field]) }}" class="d-inline-block ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this field?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No fields defined yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
