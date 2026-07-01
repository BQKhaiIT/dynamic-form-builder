@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1">Create Form</h1>
        <p class="text-muted mb-0">Define the form container first, then add fields.</p>
    </div>

    <form method="POST" action="{{ route('admin.forms.store') }}">
        @csrf
        @include('admin.forms._form', ['submitLabel' => 'Create Form'])
    </form>
@endsection
