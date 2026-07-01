@php($formModel = $form ?? null)

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input id="title" type="text" name="title" value="{{ old('title', $formModel?->title ?? '') }}" class="form-control" required>
            @error('title')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="4" class="form-control">{{ old('description', $formModel?->description ?? '') }}</textarea>
            @error('description')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select" required>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $formModel?->status?->value ?? \App\Enums\FormStatus::Draft->value) === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.forms.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        </div>
    </div>
</div>
