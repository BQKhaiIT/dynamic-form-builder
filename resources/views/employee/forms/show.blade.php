@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $form->title }}</h1>
            <p class="text-muted mb-0">{{ $form->description ?: 'No description provided.' }}</p>
        </div>
        <a href="{{ route('employee.forms.index') }}" class="btn btn-outline-secondary">Back to Forms</a>
    </div>

    <form method="POST" action="{{ route('employee.forms.submit', $form) }}">
        @csrf
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @error('form')
                    <div class="alert alert-warning">{{ $message }}</div>
                @enderror

                @if ($form->fields->isEmpty())
                    <div class="alert alert-info mb-0">
                        This form has been activated but does not contain any fields yet. Please contact an administrator.
                    </div>
                @else
                    @foreach ($form->fields as $field)
                        <div class="mb-4">
                            <label for="field_{{ $field->id }}" class="form-label">
                                {{ $field->label }}
                                @if ($field->required)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>

                            @if ($field->type === \App\Enums\FormFieldType::Text)
                                <input id="field_{{ $field->id }}" type="text" name="fields[{{ $field->id }}]" value="{{ old('fields.'.$field->id) }}" class="form-control" placeholder="{{ $field->placeholder }}">
                            @elseif ($field->type === \App\Enums\FormFieldType::Number)
                                <input id="field_{{ $field->id }}" type="number" step="any" name="fields[{{ $field->id }}]" value="{{ old('fields.'.$field->id) }}" class="form-control" placeholder="{{ $field->placeholder }}">
                            @elseif ($field->type === \App\Enums\FormFieldType::Date)
                                <input id="field_{{ $field->id }}" type="date" name="fields[{{ $field->id }}]" value="{{ old('fields.'.$field->id) }}" class="form-control">
                            @elseif ($field->type === \App\Enums\FormFieldType::Color)
                                <input id="field_{{ $field->id }}" type="color" name="fields[{{ $field->id }}]" value="{{ old('fields.'.$field->id, '#0d6efd') }}" class="form-control form-control-color">
                            @elseif ($field->type === \App\Enums\FormFieldType::Select)
                                <select id="field_{{ $field->id }}" name="fields[{{ $field->id }}]" class="form-select">
                                    <option value="">Choose an option</option>
                                    @foreach ($field->options as $option)
                                        <option value="{{ $option->value }}" @selected(old('fields.'.$field->id) === $option->value)>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            @endif

                            @error('fields.'.$field->id)
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit Response</button>
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection
