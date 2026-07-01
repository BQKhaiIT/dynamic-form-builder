@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1">Add Field</h1>
        <p class="text-muted mb-0">Create a new field for {{ $form->title }}.</p>
    </div>

    <form method="POST" action="{{ route('admin.forms.fields.store', $form) }}">
        @csrf
        @include('admin.fields._form', ['submitLabel' => 'Create Field'])
    </form>
@endsection
