@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $field->label }}</h1>
            <p class="text-muted mb-0">Field details for {{ $form->title }}.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.forms.fields.index', $form) }}" class="btn btn-outline-secondary">Back to Fields</a>
            <a href="{{ route('admin.forms.fields.edit', [$form, $field]) }}" class="btn btn-primary">Edit Field</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Type</dt>
                <dd class="col-sm-9">{{ $field->type->label() }}</dd>
                <dt class="col-sm-3">Required</dt>
                <dd class="col-sm-9">{{ $field->required ? 'Yes' : 'No' }}</dd>
                <dt class="col-sm-3">Placeholder</dt>
                <dd class="col-sm-9">{{ $field->placeholder ?: 'Not set' }}</dd>
                <dt class="col-sm-3">Sort Order</dt>
                <dd class="col-sm-9">{{ $field->sort_order }}</dd>
            </dl>

            @if ($field->options->isNotEmpty())
                <hr>
                <h2 class="h5">Options</h2>
                <ul class="list-group">
                    @foreach ($field->options as $option)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $option->label }}</span>
                            <span class="text-muted">{{ $option->value }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
