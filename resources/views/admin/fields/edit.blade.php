@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1">Edit Field</h1>
        <p class="text-muted mb-0">Update the field configuration for {{ $form->title }}.</p>
    </div>

    <form method="POST" action="{{ route('admin.forms.fields.update', [$form, $field]) }}">
        @csrf
        @method('PUT')
        @include('admin.fields._form', ['submitLabel' => 'Save Changes'])
    </form>
@endsection
