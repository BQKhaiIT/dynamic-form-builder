@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1">Edit Form</h1>
        <p class="text-muted mb-0">Update the form metadata and publication status.</p>
    </div>

    <form method="POST" action="{{ route('admin.forms.update', $form) }}">
        @csrf
        @method('PUT')
        @include('admin.forms._form', ['submitLabel' => 'Save Changes'])
    </form>
@endsection
